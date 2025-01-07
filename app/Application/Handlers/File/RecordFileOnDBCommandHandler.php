<?php

namespace App\Application\Handlers\File;

use App\Application\Commands\File\RecordFileOnDBCommand;
use App\Application\Contracts\FileUseCaseInterface;
use App\Domain\Entities\File;

class RecordFileOnDBCommandHandler
{
    public function __construct(private FileUseCaseInterface $fileUseCaseInterface)
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
        return $this->fileUseCaseInterface->saveFilesRecordOnDB($command->getUser(), $command->getStoredFiles());
    }
}
