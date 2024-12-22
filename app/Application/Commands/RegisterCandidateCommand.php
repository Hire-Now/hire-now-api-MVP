<?php

namespace App\Application\Commands;

use Illuminate\Console\Command;

class RegisterCandidateCommand extends Command
{
    protected $signature = 'Candidate:run';
    protected $description = 'Command to manage or execute operations for Candidate';

    public function handle()
    {
        $this->info('Candidate command executed successfully!');
    }
}
