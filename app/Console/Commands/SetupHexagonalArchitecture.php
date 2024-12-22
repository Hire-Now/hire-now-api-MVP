<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupHexagonalArchitecture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:hexagonal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup initial scaffolding for Hexagonal Architecture in Laravel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Setting up Hexagonal Architecture...");

        // Define the directory structure
        $directories = [
            'app/Domain/Entities',
            'app/Domain/Repositories',
            'app/Domain/Services',
            'app/Application/UseCases',
            'app/Application/DTOs',
            'app/Application/Commands',
            'app/Infrastructure/Controllers',
            'app/Infrastructure/Persistence/Eloquent',
            'app/Infrastructure/Services',
            'app/Infrastructure/Providers',
        ];

        // Create directories
        foreach ($directories as $dir) {
            if (!File::exists(base_path($dir))) {
                File::makeDirectory(base_path($dir), 0755, true);
                $this->info("Created directory: {$dir}");
            }
        }

        // Move Models to Domain/Entities
        $modelsPath = base_path('app/Models');
        $entitiesPath = base_path('app/Domain/Entities');
        if (File::exists($modelsPath)) {
            foreach (File::files($modelsPath) as $file) {
                File::move($file->getPathname(), "{$entitiesPath}/{$file->getFilename()}");
                $this->info("Moved model: {$file->getFilename()} to Domain/Entities");
            }
            File::deleteDirectory($modelsPath);
        }

        // Move Controllers to Infrastructure/Controllers
        $controllersPath = base_path('app/Http/Controllers');
        $infraControllersPath = base_path('app/Infrastructure/Controllers');
        if (File::exists($controllersPath)) {
            foreach (File::allFiles($controllersPath) as $file) {
                File::move($file->getPathname(), "{$infraControllersPath}/{$file->getFilename()}");
                $this->info("Moved controller: {$file->getFilename()} to Infrastructure/Controllers");
            }
            File::deleteDirectory($controllersPath);
        }

        // Update composer autoload
        $this->updateComposerJson();
        $this->info("Updated composer.json autoload configuration.");

        // Dump-autoload to refresh namespaces
        $this->info("Running composer dump-autoload...");
        exec('composer dump-autoload');

        $this->info("Hexagonal Architecture setup completed!");
    }

    private function updateComposerJson()
    {
        $composerPath = base_path('composer.json');
        $composerContent = json_decode(file_get_contents($composerPath), true);

        if (!isset($composerContent['autoload']['psr-4']['App\\'])) {
            $composerContent['autoload']['psr-4']['App\\'] = "app/";
        }

        file_put_contents($composerPath, json_encode($composerContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
