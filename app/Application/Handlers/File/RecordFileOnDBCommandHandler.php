<?php

namespace App\Application\Handlers\File;

use App\Application\Commands\File\RecordFileOnDBCommand;
use App\Application\Ports\Inbound\FileManagementPort;
use App\Domain\Entities\File;

class RecordFileOnDBCommandHandler
{
    public function __construct(private FileManagementPort $useCase)
    {
    }

    /**
     * handle
     *
     * @param  RecordFileOnDBCommand $command
     * @return File[]
     */
    public function handle(RecordFileOnDBCommand $command): array
    {
        return $this->useCase->saveFilesRecordOnDB($command->getUser(), $command->getStoredFiles());
    }
}
