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

        foreach ($directories as $dir) {
            if (!File::exists(base_path($dir))) {
                File::makeDirectory(base_path($dir), 0755, true);
                $this->info("Created directory: {$dir}");
            }
        }

        $modelsPath = base_path('app/Models');
        $entitiesPath = base_path('app/Domain/Entities');
        if (File::exists($modelsPath)) {
            foreach (File::files($modelsPath) as $file) {
                File::move($file->getPathname(), "{$entitiesPath}/{$file->getFilename()}");
                $this->info("Moved model: {$file->getFilename()} to Domain/Entities");
            }
            File::deleteDirectory($modelsPath);
        }

        $controllersPath = base_path('app/Http/Controllers');
        $infraControllersPath = base_path('app/Infrastructure/Controllers');
        if (File::exists($controllersPath)) {
            foreach (File::allFiles($controllersPath) as $file) {
                File::move($file->getPathname(), "{$infraControllersPath}/{$file->getFilename()}");
                $this->info("Moved controller: {$file->getFilename()} to Infrastructure/Controllers");
            }
            File::deleteDirectory($controllersPath);
        }

        $providersPath = base_path('app/Providers');
        $infraProvidersPath = base_path('app/Infrastructure/Providers');
        if (File::exists($providersPath)) {
            foreach (File::allFiles($providersPath) as $file) {
                File::move($file->getPathname(), "{$infraProvidersPath}/{$file->getFilename()}");
                $this->info("Moved provider: {$file->getFilename()} to Infrastructure/Providers");
            }
            File::deleteDirectory($providersPath);
        }

        $this->updateAppServiceProviderNamespace();

        $this->updateComposerJson();
        $this->info("Updated composer.json autoload configuration.");

        $this->info("Running composer dump-autoload...");
        exec('composer dump-autoload');

        $this->info("Hexagonal Architecture setup completed!");
    }

    private function updateAppServiceProviderNamespace()
    {
        $providerPath = base_path('app/Infrastructure/Providers/AppServiceProvider.php');
        if (File::exists($providerPath)) {
            $content = File::get($providerPath);

            $newNamespace = 'App\\Infrastructure\\Providers';
            $updatedContent = preg_replace('/namespace\s+App\\\Providers;/', "namespace {$newNamespace};", $content);

            // Guardar el archivo con el nuevo namespace
            File::put($providerPath, $updatedContent);
            $this->info("Updated namespace for AppServiceProvider.");
        } else {
            $this->error("AppServiceProvider.php not found.");
        }
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
