<?php

namespace App;

use GitLab\Client;
use Gitlab\ResultPager;
use App\Models\GitlabDto;
use Gitlab\Model\Project;

class GitLabHandler extends DvcsContract
{
    private $client;

    /**
     * GitLabHandler constructor.
     * @param SmartCommitConfig $config
     */
    public function __construct()
    {
        parent::__construct();

        $gitlabHost = $this->config->getProperty('gitlabHost');
        $gitlabToken = $this->config->getProperty('gitlabToken');

        $this->client = \Gitlab\Client::create($gitlabHost)
            ->authenticate($gitlabToken, Client::AUTH_URL_TOKEN);
    }

    /**
     * List all Projects.
     *
     * @return mixed
     */
    public function getProjects($parameters = []): \Illuminate\Support\Collection
    {
        // fetch all project
        $jsonProjects = $this->client->projects()->all($parameters);

        return $this->parseProjectArray($jsonProjects);
    }

    public function getCommits($projectId, $since = null, $until = null, $options = []): \Illuminate\Support\Collection
    {
        $proj = $this->client->repositories()->branches($projectId, $options);

        return collect($proj);
    }

    /**
     * get Project Info.
     *
     * @param $projectId
     * @return array
     */
    public function getProjectInfo($projectId) : \Illuminate\Support\Collection
    {
        $proj = $this->client->projects()->show($projectId);

        return $proj;
    }

    /**
     * List all Projects.
     *
     * @return mixed
     */
    public function getAllProjects($options = []): \Illuminate\Support\Collection
    {
        // fetch all project
        $pager = new ResultPager($this->client);

        $jsonProjects = $pager->fetchall($this->client->projects(), 'all', [$options]);

        return $this->parseProjectArray($jsonProjects);
    }

    private function parseProjectArray($jsonProjects) : \Illuminate\Support\Collection
    {
        $projsArray = collect();

        foreach ($jsonProjects as $jp) {
            $gitlab = $this->mapper->map(json_decode(json_encode($jp)), new GitlabDto());
            $projsArray->push($gitlab);
        }

        return $projsArray;
    }
}
