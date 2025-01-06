<?php

namespace App\Infrastructure\Controllers;

use App\Infrastructure\Requests\UploadFileRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class FilesController
{
    public function index()
    {
        //
    }

    public function show()
    {
        //
    }

    public function upload(Request $request)
    {
        $this->validateUploadedFiles($request->all());
        return response()->json([ 'message' => 'Files uploaded successfully.' ], 200);
    }

    public function delete()
    {
        //
    }

    public function update()
    {
        //
    }

    private function validateUploadedFiles(array $data)
    {
        $this->validateFiles($data['videos'] ?? [], [ 'video/mp4', 'video/avi', 'video/mpeg' ], 512000, 'videos');
        $this->validateLanguages($data['video_languages'] ?? [], $data['videos'] ?? [], 'video languages');

        if (isset($data['cvs'])) {
            $this->validateFiles($data['cvs'], [ 'application/pdf' ], 10240, 'CVs');
            $this->validateLanguages($data['languages'] ?? [], $data['cvs'], 'CV languages');
        }
    }

    private function validateFiles(array $files, array $allowedMimeTypes, int $maxSize, string $type)
    {
        if (empty($files)) {
            throw new BadRequestException("The $type field is required and must contain at least one file.");
        }

        foreach ($files as $file) {
            if (!isset($file['mime_type'], $file['size']) || !in_array($file['mime_type'], $allowedMimeTypes)) {
                throw new BadRequestException("Invalid $type file type. Allowed types: " . implode(', ', $allowedMimeTypes));
            }

            if ($file['size'] > $maxSize) {
                throw new BadRequestException("The $type file exceeds the maximum allowed size of {$maxSize} KB.");
            }
        }
    }

    private function validateLanguages(array $languages, array $files, string $context)
    {
        $allowedLanguages = [ 'en', 'es', 'fr', 'de', 'it', 'pt' ];//todo: tabla

        if (count($languages) !== count($files)) {
            throw new BadRequestException("The number of $context must match the number of files.");
        }

        foreach ($languages as $language) {
            if (!in_array($language, $allowedLanguages)) {
                throw new BadRequestException("Invalid language: $language. Allowed languages: " . implode(', ', $allowedLanguages));
            }
        }
    }

}
