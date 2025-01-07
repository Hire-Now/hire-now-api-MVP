<?php

namespace App\Infrastructure\Controllers;

use App\Application\Commands\File\RecordFileOnDBCommand;
use App\Application\Commands\File\SetFileForScanCommand;
use App\Application\Commands\File\UploadNewFileCommand;
use App\Application\Handlers\File\RecordFileOnDBCommandHandler;
use App\Application\Handlers\File\SetFileForScanCommandHandler;
use App\Application\Handlers\File\UploadNewFileCommandHandler;
use App\Infrastructure\Requests\UploadFileRequest;

class FilesController
{

    public function __construct(
        private readonly UploadNewFileCommandHandler $uploadNewFileCommandHandler,
        private readonly RecordFileOnDBCommandHandler $recordFileOnDBCommandHandler,
        private readonly SetFileForScanCommandHandler $setFileForScanCommandHandler
    ) {
    }
    public function index()
    {
        //todo: solo el usuario dueno o el del rol correspondiente podra visualizar archivos
    }

    public function show()
    {
        //todo: solo el usuario dueno o el del rol correspondiente podra visualizar archivos
    }

    public function upload(UploadFileRequest $request)
    {
        try {
            $user = $request->attributes->get('user_model');
            $request = $request->validated();

            $uploadNewFileCommand = new UploadNewFileCommand($user, $request);
            $storedFiles = $this->uploadNewFileCommandHandler->handle($uploadNewFileCommand);

            $recordFileOnDBCommand = new RecordFileOnDBCommand($user, $storedFiles);
            $filesRecords = $this->recordFileOnDBCommandHandler->handle($recordFileOnDBCommand);

            $setFileForScanCommand = new SetFileForScanCommand($filesRecords);
            $this->setFileForScanCommandHandler->handle($setFileForScanCommand);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'File/s uploaded successfully and currently queued for a scan.',
                'data'    => array_map(function ($saveFile) {
                    return $saveFile->toArray();
                }, $filesRecords)
            ], 200);
        } catch (\Throwable $th) {
            logger()->error("Error in FilesController@upload: {$th->getMessage()}", [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Failed to upload the file/s, please try again later.',
                'data'    => []
            ], 500);
        }
    }

    public function delete()
    {
        //
    }

    public function update()
    {
        //
    }
}
