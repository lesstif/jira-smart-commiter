<?php

namespace App\Commands;

use App\DvcsContract;
use App\SmartCommitConfig;
use DateTime;
use Carbon\Carbon;
use App\DvcsConnectorFactory;
use Illuminate\Console\Scheduling\CacheMutex;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;
use Illuminated\Console\WithoutOverlapping;

class GitlabCommitCommand extends Command
{
    // for preventing command overlap
    use WithoutOverlapping;

    protected $mutexStrategy = 'file';

    //protected $mutexTimeout = 0; // milliseconds

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
                exit(-1);
            }

            //Carbon::createFromFormat();
        }

        // step 1. load project config
        $projs = SmartCommitConfig::loadProjects();

        // step 2.
        $idx = 0;
        foreach ($projs as $p) {
            // sync now
            $dvcs = DvcsConnectorFactory::createByType($p->dvcsType, $p->apiVersion);

            $commits = $dvcs->getCommits($p->id, $since, $until);

            // fetch commit
            //$this->info("$p->name");
            if ($idx++ == 10) dd(1);
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
