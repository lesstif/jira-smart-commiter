<?php
namespace App;

use App\Models\GitlabDto;
use App\Models\ProjectDto;
use \GitLab\Client;
use Gitlab\Model\Project;
use \Gitlab\ResultPager;

use Illuminate\Support\Facades\Log;
use JsonMapper;

class GitLabHandler extends DvcsContract
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

        $this->client = \Gitlab\Client::create($gitlabHost)
            ->authenticate($gitlabToken, Client::AUTH_URL_TOKEN);
    }

    /**
     * List all Projects
     *
     * @return mixed
     */
    public function getProjects($parameters = []): array
    {
        // fetch all project
        $jsonProjects = $this->client->projects()->all($parameters);

        return $this->parseProjectArray($jsonProjects);
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
        $proj = $this->client->projects()->show($projectId);

        return $proj;
    }

    /**
     * List all Projects
     *
     * @return mixed
     */
    public function getAllProjects($options = []): array
    {
        // fetch all project
        $pager = new ResultPager($this->client);

        $jsonProjects = $pager->fetchall($this->client->projects(), 'all', [$options]);

        return $this->parseProjectArray($jsonProjects);
    }

    private function parseProjectArray($jsonProjects) : array
    {
        $projsArray = [];

        foreach ($jsonProjects as $jp) {
            $gitlab = $this->mapper->map(json_decode(json_encode($jp)), new GitlabDto());
            $pd = new GitlabDto(null, 'V4');
            $projsArray[] = $pd;
        }

        return $projsArray;
    }

}
