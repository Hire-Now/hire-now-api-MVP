<?php

namespace App\Application\UseCases;

use App\Application\Contracts\FileUseCaseInterface;
use App\Domain\Entities\File;
use App\Domain\Repositories\FileRepositoryInterface;
use App\Infrastructure\Jobs\ScanFileWithVirusTotal;
use App\Infrastructure\Persistence\Eloquent\Models\File as ModelsFile;
use App\Infrastructure\Persistence\Eloquent\Models\Role;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileUseCase implements FileUseCaseInterface
{
    public function __construct(private readonly FileRepositoryInterface $fileRepository)
    {
    }

    /**
     * uploadFileToStorage
     *
     * @param User $user The user who owns the files.
     * @param array $files An array of file details, including file, language, visibility, and type.
     *
     * @return File[] An array of File objects representing the uploaded files.
     */
    public function uploadFileToStorage(User $user, array $files): array
    {
        try {
            $filesDetails = [];
            $role = $this->getMainRole($user->roles);

            foreach ($files['files'] as $fileParams) {
                $fileName = $this->createFileName($fileParams["file"]);
                $directory = "$role/{$user->id}/{$fileParams["type"]}";

                $filePath = $fileParams["file"]->storeAs($directory, $fileName, [
                    'disk'       => 'uploads',
                    'visibility' => $fileParams['visibility'],
                ]);

                $fileSizeInBytes = $fileParams["file"]->getSize();
                $fileSizeInKB = round($fileSizeInBytes / 1024, 2);

                $filesDetails[] = new File(
                    null,
                    $user->id,
                    $fileParams["file"]->getClientOriginalExtension(),
                    $fileName,
                    $filePath,
                    $fileSizeInKB,
                    $fileParams['visibility'],
                    $fileParams['language'],
                    null,
                    null,
                    null,
                    $fileParams["is_cv"] ?? false,
                    $fileParams["is_main_cv"] ?? false
                );
            }

            return $filesDetails;
        } catch (\Throwable $th) {
            throw new \Exception("File could not be uploaded due to an error", 0, $th);
        }
    }

    private function getMainRole(Collection $roles)
    {
        return $roles[0]->name;
    }

    private function createFileName(UploadedFile $file): string
    {
        $slug = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = $file->getClientOriginalExtension();
        return $slug . '-' . uniqid() . '.' . $extension;
    }

    /**
     * saveFilesRecordOnDB
     *
     * @param  User $user
     * @param  File[] $storedFiles
     * @return File[]
     */
    public function saveFilesRecordOnDB(User $user, array $storedFiles): array
    {
        try {
            $index = 0;
            $role = $this->getMainRole($user->roles);

            foreach ($storedFiles as $storedFile) {
                $storedFiles[$index] = $this->fileRepository->create($user, $storedFile, $role);
                $index++;
            }

            return $storedFiles;
        } catch (\Throwable $th) {
            throw new \Exception("File could not be saved into database due to an error", 0, $th);
        }
    }

    public function setFileIntoQueueForScan(array $savedFiles): void
    {
        try {
            foreach ($savedFiles as $savedFile) {
                //todo: esto debe ir a un adaptador de salida
                ScanFileWithVirusTotal::dispatch($savedFile->getId());
            }
        } catch (\Throwable $th) {
            throw new \Exception("File could not be saved into database due to an error", 0, $th);
        }
    }

    public function getFileWithCustomizedConditions(array $queryConditions): File
    {
        try {
            $fileEntity = $this->fileRepository->getFileWithCustomizedConditions($queryConditions);

            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile(storage_path("app/{$fileEntity->getFilePath()}"));

            dd($pdf->getText());
        } catch (\Throwable $th) {
            throw new \Exception("File could not be found due to an error", 0, $th);
        }
    }
}
