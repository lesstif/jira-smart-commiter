<?php
namespace App;

use \GitLab\Client;
use \Gitlab\ResultPager;

class GitLabHandler extends DvcsContract
{
    private $client;

    public function __construct()
    {
        $this->envLoad();

        $this->client = \Gitlab\Client::create($this->gitHost)
            ->authenticate($this->gitToken, Client::AUTH_URL_TOKEN)
        ;
    }

    /**
     * List all Projects
     *
     * @return mixed
     */
    public function getProjects($parameters = []): array
    {
        // fetch all project
        $pager = new ResultPager($this->client);

        return $pager->fetchall($this->client->projects(), 'all', [$parameters]);
    }

    public function getCommits($since): array
    {
        // TODO: Implement getCommits() method.
    }
}