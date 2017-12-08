<?php

namespace App\Commands;

use App\Models\Settings;
use App\SmartCommitConfig;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class InitCommand extends Command
{
    private $config;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init
                            {--config= : Use file instead of settings.json}';

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
        $config = $this->option('config');

        if (empty($config))
            $config= 'settings.json';

        $this->config->saveSettings($config);

        // check and create file mutex directory
        if (!Storage::exists('app')) {
            Storage::makeDirectory('app');
        }

        $this->info('initial config generation done.!');
        $this->info("Edit the '~/.smartcommit/${config}' to suit your environment. ");
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
