<?php

namespace App\Commands;

use App\Models\Settings;
use App\SmartCommitConfig;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\Storage;

class InitCommand extends Command
{
    private $config ;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initializing Settings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->config = new SmartCommitConfig(new Settings());
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        //Storage::put("reminders.txt", "Task 1");
        $this->config->saveSettings();

        $this->info('initial config generation done.!');
        $this->info("Edit the '~/.smartcommit/settings.json' to suit your environment. ");
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
