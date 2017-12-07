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

    private $options;

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

        $this->options = [
            'page' => 1,
            'per_page' => 100,
            'order_by' =>  'created_at',
            'sort' => 'asc',
        ];
    }

    /**
     * List all Projects
     *
     * @return mixed
     */
    public function getProjects($parameters = []): array
    {
        $parameters = array_replace($this->options, $parameters);

        $json = $this->client->request('projects/', $parameters);

        $projs = $this->mapper->mapArray(
            $json, array(), GitlabDto::class
        );

        // add property
        $projs = array_map(function($proj) {
            $proj->apiVersion = 'V3';
        }, $projs);

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
    public function getAllProjects($parameters = []): array
    {
        $parameters = array_replace($this->options, $parameters);

        $json = $this->client->request('projects/', $parameters);

        $projs = $this->mapper->mapArray(
            $json, array(), GitlabDto::class
            //$json, array(), null
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
        parent::saveProjects($projects, $file);
    }
}
