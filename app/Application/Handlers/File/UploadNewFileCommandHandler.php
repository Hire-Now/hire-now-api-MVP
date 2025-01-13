<?php

namespace App\Application\Handlers\File;

use App\Application\Commands\File\UploadNewFileCommand;
use App\Application\Ports\Inbound\FileManagementPort;
use App\Domain\Entities\File;

class UploadNewFileCommandHandler
{
    public function __construct(private FileManagementPort $useCase)
    {
    }

    /**
     * handle
     *
     * @param  UploadNewFileCommand $command
     * @return File[]
     */
    public function handle(UploadNewFileCommand $command): array
    {
        return $this->useCase->uploadFileToStorage($command->getUser(), $command->getFiles());
    }
}
