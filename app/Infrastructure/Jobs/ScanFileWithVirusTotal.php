<?php

namespace App\Infrastructure\Jobs;

use App\Infrastructure\Persistence\Eloquent\Models\File;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ScanFileWithVirusTotal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $fileId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting ScanFileWithVirusTotal Job for file ID: {$this->fileId}");
        $fileRecord = File::find($this->fileId);

        if (!$fileRecord) {
            Log::error("File not found for ID: {$this->fileId}");
            return;
        }

        Log::info("File record found: ", $fileRecord->toArray());

        $filePath = storage_path("app/private/{$fileRecord->path}");
        if (!file_exists($filePath)) {
            Log::error("File does not exist at path: {$filePath}");
            return;
        }
        $fileContent = file_get_contents($filePath);

        $uploadResponse = Http::withHeaders([
            'x-Apikey' => config('app.vt.api_key')
        ])->attach('file', $fileContent, 'filename')
            ->post(config('app.vt.base_uri'));

        if ($uploadResponse->successful()) {
            $fileRecord->update([
                'vt_scan_id' => $uploadResponse->json()['data']['id'], // ID del archivo en VirusTotal
                'status'     => 'scanning', // Establecemos como en proceso de escaneo
            ]);

            // Esperar el resultado del escaneo
            $this->checkScanResult($fileRecord);
        } else {
            $fileRecord->update([
                'status' => 'failed',
            ]);
        }
    }

    protected function checkScanResult(File $fileRecord)
    {
        // Repetir la consulta cada cierto tiempo (por ejemplo, cada 30 segundos)
        $response = Http::withHeaders([
            'x-apikey' => env('VIRUS_TOTAL_API_KEY'),
        ])->get("https://www.virustotal.com/api/v3/files/{$fileRecord->vt_scan_id}");

        if ($response->successful()) {
            $scanData = $response->json()['data']['attributes']['last_analysis_stats'];

            if ($scanData['malicious'] > 0) {
                // Si el archivo es malicioso, eliminamos el archivo y actualizamos la base de datos
                Storage::delete($fileRecord->path);
                $fileRecord->update([
                    'status' => 'unsafe',
                ]);
            } else {
                $fileRecord->update([
                    'status' => 'safe',
                ]);
            }
        } else {
            // Log::channel('query_logs')->info('Query executed:', $logData);

            // Si la respuesta falla, reintentamos después de un tiempo
            $this->release(30); // Reintenta el trabajo después de 30 segundos
        }
    }
}
