<?php

namespace App\Application\Handlers\Resume;

use App\Application\Commands\Resume\ExtractTextFromResumeAndEnhanceItCommand;
use App\Application\Contracts\FileUseCaseInterface;

class ExtractTextFromResumeCommandHandler
{
    public function __construct(private readonly FileUseCaseInterface $fileUseCase)
    {
    }

    public function handle(ExtractTextFromResumeAndEnhanceItCommand $command)
    {
        return $this->fileUseCase->extractTextFromFileAndEnhanceIt($command->getResumeContent(), $command->getLanguageFile());
    }
}
