<?php

namespace App\Infrastructure\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryLoggerService
{
    public static function enable(): void
    {
        if (config('app.enable_query_log', false)) {
            DB::listen(function ($query) {
                $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
                $origin = [
                    'file'     => $backtrace[1]['file'] ?? 'N/A',
                    'line'     => $backtrace[1]['line'] ?? 'N/A',
                    'function' => $backtrace[2]['function'] ?? 'N/A',
                ];

                $logData = [
                    'sql'         => $query->sql,
                    'bindings'    => $query->bindings,
                    'time'        => $query->time,
                    'executed_at' => now()->toDateTimeString(),
                    'origin'      => $origin,
                ];

                Log::channel('query_logs')->info('Query executed:', $logData);
            });
        }
    }
}
