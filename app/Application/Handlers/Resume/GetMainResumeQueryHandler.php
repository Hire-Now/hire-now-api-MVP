<?php

namespace App\Application\Handlers\Resume;

use App\Application\Commands\Resume\ExtractTextFromResumeCommand;
use App\Application\Commands\Resume\GetMainResumeCommand;
use App\Application\Contracts\FileUseCaseInterface;
use App\Application\Queries\Resume\GetMainResumeQuery;

class GetMainResumeQueryHandler
{
    public function __construct(private readonly FileUseCaseInterface $fileUseCase)
    {
    }

    public function handle(GetMainResumeQuery $command): string
    {
        return $this->fileUseCase->getFileWithCustomizedConditions([
            'owner_id'              => $command->getUserId(),
            'metadata->uploaded_by' => $command->getRole(),
            'metadata->is_cv'       => $command->getIsCV(),
            'metadata->is_main_cv'  => $command->getIsMainCV()
        ]);
    }
}
