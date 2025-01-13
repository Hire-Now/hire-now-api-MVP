<?php

namespace App\Application\UseCases;

use App\Application\Ports\Inbound\FileManagementPort;
use App\Domain\Entities\File;
use App\Domain\Ports\Outbound\FileRepositoryPort;
use App\Infrastructure\Adapter\OpenAIAdapter;
use App\Infrastructure\Jobs\ScanFileWithVirusTotal;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;

class FileUseCase implements FileManagementPort
{
    public function __construct(private readonly FileRepositoryPort $fileRepository, private readonly OpenAIAdapter $openAIAdapter)
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

    public function getFileWithCustomizedConditions(array $queryConditions): array
    {
        try {
            $fileEntity = $this->fileRepository->getFileWithCustomizedConditions($queryConditions);

            return [
                'file_content'  => file_get_contents(storage_path("app/uploads/{$fileEntity->getFilePath()}")),
                'language_file' => $fileEntity->getLanguage()
            ];
        } catch (\Throwable $th) {
            throw new \Exception("File could not be found due to an error", 0, $th);
        }
    }

    public function extractTextFromFileAndEnhanceIt(string $fileContent, string $languageFile): string
    {
        try {
            $parser = new Parser();

            $parsedPDF = $parser->parseContent($fileContent);
            $text = $this->ensureUtf8Encoding($parsedPDF->getText());

            $structuredCurriculum = $this->openAIAdapter->extractDataFromText($text, $languageFile);
            dd($structuredCurriculum);
            return '';
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    private function ensureUtf8Encoding(string $text): string
    {
        $encoding = mb_detect_encoding($text, [ 'UTF-8', 'ISO-8859-1', 'Windows-1252' ], true);
        if ($encoding !== 'UTF-8') {
            $text = mb_convert_encoding($text, 'UTF-8', $encoding);
        }
        return $text;
    }
}
