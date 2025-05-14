<?php

namespace RecursiveTree\Seat\PackageNotifications\Commands;

use Illuminate\Console\Command;
class CheckVersions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package-notifications:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications when packages are outdated.';

    /**
     * Execute the console command.
     */
    public function handle(){
        return $this::SUCCESS;
    }
}