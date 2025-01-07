<?php

namespace App\Application\Handlers\File;

use App\Application\Commands\File\UploadNewFileCommand;
use App\Application\Contracts\FileUseCaseInterface;
use App\Domain\Entities\File;

class UploadNewFileCommandHandler
{
    public function __construct(private FileUseCaseInterface $fileUseCaseInterface)
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
        return $this->fileUseCaseInterface->uploadFileToStorage($command->getUser(), $command->getFiles());
    }
}
