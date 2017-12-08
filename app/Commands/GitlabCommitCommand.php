<?php

namespace App\Commands;

use DateTime;
use Carbon\Carbon;
use App\DvcsConnectorFactory;
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
        $sinceOpt = $this->option('since');
        $untilOpt = $this->option('until') ?? 'now';

        $since = null;
        $until = null;

        $dateFormats = ['Y-m-d H:i:s', 'Y-m-d H:i', 'Y-m-d H', 'Y-m-d'];

        if (! empty($sinceOpt)) {
            foreach ($dateFormats as $format) {
                $since = DateTime::createFromFormat($format, $sinceOpt);
                if ($since instanceof DateTime) {
                    break;
                }
            }

            if (! $since instanceof DateTime) {
                $this->error("'$sinceOpt' Invalid DateTime Format! ");
            }

            //Carbon::createFromFormat();
        }

        // step 1. load project config
        $projs = [];

        // steap2.
        foreach ($projs as $p) {
            // sync now

            // fetch commit
        }

        // DvcsConnectorFactory::createByType('git');

        $this->info("commit fetch function not yet impl! $sinceOpt, $untilOpt");
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
