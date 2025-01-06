<?php

namespace App\Infrastructure\Controllers;

use App\Infrastructure\Jobs\ScanFileWithVirusTotal;
use App\Infrastructure\Persistence\Eloquent\Models\File;
use App\Infrastructure\Requests\UploadFileRequest;
use Illuminate\Support\Str;

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

    public function upload(UploadFileRequest $request)
    {
        try {
            $user = $request->attributes->get('user_model');

            $request = $request->validated();

            $file = $request['videos'][0];

            // Genera un slug a partir del nombre del archivo y obtiene su extensión
            $slug = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $extension = $file->getClientOriginalExtension();

            $fileSizeInBytes = $file->getSize();

            $fileSizeInKB = round($fileSizeInBytes / 1024, 2);

            $filename = $slug . '-' . uniqid() . '.' . $extension;

            // Almacena el archivo en el storage bajo el directorio 'uploads' con el nombre generado
            $path = $file->storeAs('uploads', $filename);

            // Guarda la información del archivo en la base de datos
            $fileRecord = $user->files()->create([
                'name'        => $filename,
                'path'        => $path,
                'type'        => $extension,
                'size'        => $fileSizeInKB,
                'language'    => $request['video_languages'][0],
                'vt_scan_id'  => '_',
                'scan_status' => 'pending',
                'metadata'    => json_encode([
                    'uploaded_by' => $user->roles[0]->name,
                    'visibility'  => 'private',
                ]),
            ]);

            // Encola el trabajo para verificar el archivo
            ScanFileWithVirusTotal::dispatch($fileRecord->id);

            return response()->json([
                'message' => 'Archivo subido correctamente. Está siendo procesado.',
            ]);

            return response()->json([ 'message' => 'Files uploaded successfully.' ], 200);
        } catch (\Throwable $th) {
            dd($th);
            logger()->error("Error in UserController@show: {$th->getMessage()}", [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Failed to get the user, please try again later.',
                'data'    => []
            ], 500);
        }
    }

    public function delete()
    {
        //
    }

    public function update()
    {
        //
    }
}
