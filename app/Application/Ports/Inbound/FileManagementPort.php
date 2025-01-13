<?php

namespace App\Application\Ports\Inbound;

use App\Infrastructure\Persistence\Eloquent\Models\User;

interface FileManagementPort
{
    public function uploadFileToStorage(User $user, array $files): array;
    public function saveFilesRecordOnDB(User $user, array $storedFiles): array;
    public function setFileIntoQueueForScan(array $savedFiles): void;
    public function getFileWithCustomizedConditions(array $queryConditions);
    public function extractTextFromFileAndEnhanceIt(string $fileContent, string $languageFile): string;
}
