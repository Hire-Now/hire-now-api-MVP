<?php

namespace App\Application\Handlers\Resume;

use App\Application\Commands\Resume\ExtractTextFromResumeAndEnhanceItCommand;
use App\Application\Ports\Inbound\FileManagementPort;

class ExtractTextFromResumeCommandHandler
{
    public function __construct(private readonly FileManagementPort $useCase)
    {
    }

    public function handle(ExtractTextFromResumeAndEnhanceItCommand $command)
    {
        return $this->useCase->extractTextFromFileAndEnhanceIt($command->getResumeContent(), $command->getLanguageFile());
    }
}
