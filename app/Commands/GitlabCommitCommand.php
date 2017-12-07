<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class GitlabCommitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commit:fetch
                            {--since= : Only commits after or on this date will be returned in format YYYY-MM-DDTHH:MM:SSZ}
                            {--until= : Only commits before or on this date will be returned }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get a list of repository commits in a project.';

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
        $since = $this->option('since');
        $until = $this->option('until');

        $this->info("commit fetch function not yet impl! $since, $until");
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
