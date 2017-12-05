<?php

namespace App\Commands;

use App\DvcsConnectorFactory;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\SmartCommitBaseCommand;

class GitlabProjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:create-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate all gitlab project owned by you.';

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
     * @return mixed
     */
    public function handle(): void
    {
        $dvcsHandler = DvcsConnectorFactory::create();

        $projects = $dvcsHandler->getProjects();

        foreach($projects as $p) {
            dump($p);
        }

        //$this->info('project list create function not yet impl!');
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
