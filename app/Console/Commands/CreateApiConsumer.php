<?php

namespace App\Console\Commands;

use App\Infrastructure\Persistence\Eloquent\Models\ApiConsumer;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateApiConsumer extends Command
{//php artisan api:consumer:create "Internal API Consumer" --description="Internal Service Consumer"
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:consumer:create {name} {--description=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new API consumer';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $clientId = Str::uuid();
        $clientSecret = Str::random(32);

        ApiConsumer::create([
            'name'          => $name,
            'client_id'     => $clientId,
            'client_secret' => bcrypt($clientSecret),
            'description'   => $this->option('description'),
        ]);

        $this->info("API Consumer created successfully!");
        $this->line("Client ID: {$clientId}");
        $this->line("Client Secret: {$clientSecret} (store it securely)");
    }
}
