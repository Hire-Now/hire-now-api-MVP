<?php

namespace App\Application\Handlers\Resume;

use App\Application\Ports\Inbound\FileManagementPort;
use App\Application\Queries\Resume\GetMainResumeQuery;

class GetMainResumeQueryHandler
{
    public function __construct(private readonly FileManagementPort $useCase)
    {
    }

    public function handle(GetMainResumeQuery $command): array
    {
        return $this->useCase->getFileWithCustomizedConditions([
            'owner_id'              => $command->getUserId(),
            'metadata->uploaded_by' => $command->getRole(),
            'metadata->is_cv'       => $command->getIsCV(),
            'metadata->is_main_cv'  => $command->getIsMainCV()
        ]);
    }
}
