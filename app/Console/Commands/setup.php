<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Command;

class setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup {--users=10} {--tasks=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to setup quickly the application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line("<fg=magenta>Generating users !\n");
        $main = $this->output->createProgressBar(100);
        User::factory($this->option('users'))->create();
        $main->finish();
        $this->line("<fg=green>\nDone !\n");

        $this->line("<fg=magenta>Generating tasks !\n");
        $main = $this->output->createProgressBar(100);
        Task::factory($this->option('users'))->create();
        $main->finish();
        $this->line("<fg=green>\nDone !\n");

        $this->line("<fg=cyan>Everything has been set up !\n");
    }
}
