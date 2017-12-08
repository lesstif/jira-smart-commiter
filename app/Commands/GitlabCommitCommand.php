<?php

namespace App\Commands;

use App\Exceptions\SmartCommitException;
use DateTime;
use Carbon\Carbon;
use App\Models\ProjectDto;
use App\SmartCommitConfig;
use App\DvcsConnectorFactory;
use Illuminate\Console\Scheduling\Schedule;
use Illuminated\Console\WithoutOverlapping;
use LaravelZero\Framework\Commands\Command;

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
                            {--until= : Only commits before or on this date will be returned }
                            {--config= : Use file instead of settings.json}
                            {--project= : Save to out instead of projects.json}
                            {--idOrName= : fetch  specifified project\' commit only.}';

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

        $config = $this->option('config');
        $project = $this->option('project');

        $idOrName = $this->option('idOrName');

        if (empty($config)) {
            $config = 'settings.json';
        }

        if (empty($project)) {
            $project = 'projects.json';
        }

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
        $projs = SmartCommitConfig::loadProjects($project);

        if (!empty($idOrName)) {
            $this->info("Project $idOrName ");
            $proj = $projs->where('id', $idOrName)->first() ?? $projs->where('name', $idOrName)->first();

            if ($proj === null){
                throw new SmartCommitException("Project '$idOrName' not found!");
            }

            // sync now
            $dvcs = DvcsConnectorFactory::createByType($proj->dvcsType, $proj->apiVersion);

            $commits = $dvcs->getCommits($proj->id, $since, $until);

            // fetch commit
            $this->info("$proj->name has total commit:".count($commits));
            if (count($commits) > 0) {
                dump($commits);
            }
        }

        // step 2.
        foreach ($projs as $p) {
            $this->fetchCommit($p, null, null);
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

    private function fetchCommit(ProjectDto $proj, $since, $until) : \Illuminate\Support\Collection
    {
        // sync now
        $dvcs = DvcsConnectorFactory::createByType($proj->dvcsType, $proj->apiVersion);

        $commits = $dvcs->getCommits($proj->id, $since, $until);

        // fetch commit
        $this->info("$proj->name has total commit:".count($commits));
        if (count($commits) > 0) {
            dump($commits);
        }

        return $commits;
    }
}
