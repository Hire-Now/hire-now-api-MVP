<?php

namespace App\Application\Handlers\File;

use App\Application\Commands\File\SetFileForScanCommand;
use App\Application\Ports\Inbound\FileManagementPort;
use App\Domain\Entities\File;

class SetFileForScanCommandHandler
{
    public function __construct(private FileManagementPort $useCase)
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
        $this->useCase->setFileIntoQueueForScan($command->getSavedFiles());
    }
}
