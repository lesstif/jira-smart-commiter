<?php
namespace App;

use App\Models\GitlabDto;
use \GitLab\Client;
use \Gitlab\ResultPager;

use Illuminate\Support\Facades\Log;
use App\Exceptions\NotImplmentationException;
use Illuminate\Support\Facades\Storage;

class GitLabV3Handler extends DvcsContract
{
    private $client;

    /**
     * GitLabHandler constructor.
     * @param SmartCommitConfig $config
     */
    public function __construct(SmartCommitConfig $config)
    {
        parent::__construct($config);

        $gitlabHost = $this->getProperty('gitlabHost');
        $gitlabToken = $this->getProperty('gitlabToken');

        $this->client = new HttpClient($gitlabHost, $gitlabToken);
    }

    /**
     * List all Projects
     *
     * @return mixed
     */
    public function getProjects($parameters = []): array
    {
        $default = [
            'page' => 1,
            'per_page' => 20,
            'order_by' =>  'created_at',
            'sort' => 'asc',
        ];

        $json = $this->client->request('projects/');

        $projs = $this->mapper->mapArray(
            $json, array(), GitlabDto::class
        );

        return $projs;
    }

    public function getCommits($projectId, $since = null, $until = null, $options = []): array
    {
        $proj = $this->client->repositories()->branches($projectId, $options);

        return $proj;
    }

    /**
     * get Project Info
     *
     * @param $projectId
     * @return array
     */
    public function getProjectInfo($projectId) : array
    {
        $proj = $this->client->api('projects')->show($projectId);

        return $proj;
    }

    /**
     * List all Projects
     *
     * @return mixed
     */
    public function getAllProjects($options = []): array
    {
        $default = [
            'page' => 1,
            'per_page' => 100,
            'order_by' =>  'created_at',
            'sort' => 'asc',
        ];

        $json = $this->client->request('projects/');

        $projs = $this->mapper->mapArray(
            //$json, array(), GitlabDto::class
            $json, array(), null
        );

        return $projs;
    }

    /**
     * save DVCS Project Info
     *
     * @param $projects
     * @return mixed
     */
    public function saveProjects($projects, $file="projects.json") : void
    {
        Log::info('saveProjects : ');

        // TODO: Implement saveProjects() method.
        //return NotImplmentationException("saveProjects() not implmented");
        //$json = json_encode($this->settings, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        /*
        $prevProjs = null;

        if (Storage::exists($file)) {
            $now = Carbon::now();
            $now->setToStringFormat('Y-m-d-H-i-s');
            Storage::move($file, $file . '-' . $now);
        }
        */
        $json = json_encode($projects, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        Storage::put($file, $json);
    }
}
