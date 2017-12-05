<?php
namespace App;

use \GitLab\Client;
use \Gitlab\ResultPager;

use Illuminate\Support\Facades\Log;

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

        dump(['$gitlabToken' => $gitlabToken]);
        $this->client = new \Gitlab\Client($gitlabHost . '/api/v3/');
        $this->client->authenticate($gitlabToken, \Gitlab\Client::AUTH_URL_TOKEN);
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

        //$parameters = array_replace($default, $parameters);
        $gitlabHost = $this->getProperty('gitlabHost');
        $gitlabToken = $this->getProperty('gitlabToken');

        $this->client = new \Gitlab\Client($gitlabHost . '/api/v3/');
        $this->client->authenticate($gitlabToken, \Gitlab\Client::AUTH_URL_TOKEN);

        $jsonProjects = $this->client->api('projects')->all();

        $mapper = new JsonMapper();

        $projsArray = $mapper->mapArray(
            $jsonProjects, array(), 'Project'
        );

        return $projsArray;
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
        // fetch all project
        $pager = new ResultPager($this->client);

        return $pager->fetchall($this->client->projects(), 'all', [$options]);
    }
}
