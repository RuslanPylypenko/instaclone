<?php

namespace App\Console\Commands\User;

use App\Jobs\User\CheckInactiveJob;
use Illuminate\Console\Command;

class CheckInactiveCommand extends Command
{
    protected $signature = 'app:check-inactive-command';

    protected $description = 'Check users who not visit network for a log time';

    public function handle()
    {
        CheckInactiveJob::dispatch();

        $this->info('Process...');
    }
}
