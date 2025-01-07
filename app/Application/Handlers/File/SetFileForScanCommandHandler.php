<?php

namespace App\Application\Handlers\File;

use App\Application\Commands\File\SetFileForScanCommand;
use App\Application\Contracts\FileUseCaseInterface;
use App\Domain\Entities\File;

class SetFileForScanCommandHandler
{
    public function __construct(private FileUseCaseInterface $fileUseCaseInterface)
    {
    }

    /**
     * handle
     *
     * @param  SetFileForScanCommand $command
     * @return File[]
     */
    public function handle(SetFileForScanCommand $command): void
    {
        $this->fileUseCaseInterface->setFileIntoQueueForScan($command->getSavedFiles());
    }
}
