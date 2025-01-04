<?php

namespace App\Console\Commands;

use App\Infrastructure\Persistence\Eloquent\Models\Role;
use Illuminate\Console\Command;

class CreateAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:admin-role:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Role::create([
            'name'        => "admin",
            'description' => "Makes a user an admin."
        ]);


        $this->info("Admin role created successfully!");
    }
}
