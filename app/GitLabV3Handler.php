<?php

namespace App;

use App\Models\GitlabDto;
use function foo\func;

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

    public function getProjects($parameters = []): \Illuminate\Support\Collection
    {
        $parameters = array_replace($this->options, $parameters);

        $json = $this->client->request('projects/', $parameters);

        $projs = $this->mapper->mapArray(
            $json, [], GitlabDto::class
        );

        // add property
        $projs->transform(function ($item, $key) {
            $item->setDvcs('gitlab', 'V3');
            return $item;
        });

        return $projs;
    }

    public function getCommits($projectId, $since = null, $until = null, $options = []): \Illuminate\Support\Collection
    {
        $proj = $this->client->repositories()->branches($projectId, $options);

        return $proj;
    }

    /**
     * get Project Info.
     *
     * @param $projectId
     * @return array
     */
    public function getProjectInfo($projectId) : \Illuminate\Support\Collection
    {
        $proj = $this->client->api('projects')->show($projectId);

        return $proj;
    }

    /**
     * List all Projects.
     *
     * @return mixed
     */
    public function getAllProjects($parameters = []): \Illuminate\Support\Collection
    {
        $parameters = array_replace($this->options, $parameters);

        $json = $this->client->request('projects/', $parameters);

        $projs = $this->mapper->mapArray(
            $json, collect(), GitlabDto::class
        );

        $projs->transform(function ($item, $key) {
            $item->setDvcs('gitlab', 'V3');
            return $item;
        });

        return $projs;
    }

    /**
     * save DVCS Project Info.
     *
     * @param $projects
     * @return mixed
     */
    public function saveProjects($projects, $file = 'projects.json') : void
    {
        parent::saveProjects($projects, $file);
    }
}
