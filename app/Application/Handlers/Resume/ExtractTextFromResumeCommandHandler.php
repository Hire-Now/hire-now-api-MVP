<?php

namespace App\Application\Handlers\Resume;

use App\Application\Commands\Resume\ExtractTextFromResumeCommand;
use App\Application\Contracts\FileUseCaseInterface;

class ExtractTextFromResumeCommandHandler
{
    public function __construct(private readonly FileUseCaseInterface $fileUseCase)
    {
    }

    public function handle(ExtractTextFromResumeCommand $command)
    {
        // return $this->fileUseCase->
    }
}
