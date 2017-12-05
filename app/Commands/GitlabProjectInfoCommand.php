<?php

namespace App\Commands;

use App\DvcsConnectorFactory;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class GitlabProjectInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:info {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get Project Info';

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
        $id = $this->argument('id');

        $dvcsHandler = DvcsConnectorFactory::create();

        $proj = $dvcsHandler->getProjectInfo($id);

        dump($proj);

        $this->info('project info function not yet impl!');
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
