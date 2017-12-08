<?php

namespace App;

use App\Models\GitlabDto;

class GitLabV3Handler extends DvcsContract
{
    private $client;

    private $options;

    /**
     * GitLabHandler constructor.
     * @param SmartCommitConfig $config
     */
    public function __construct()
    {
        parent::__construct();
        $this->setDvcsTypeAndVersion('gitlab', 'v3');

        $gitlabHost = $this->config->getProperty('gitlabHost');
        $gitlabToken = $this->config->getProperty('gitlabToken');

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
        $url = sprintf("%d/repository/commits", $projectId);
        $proj = $this->client->request($url , $options);

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

        $response = $this->client->request('projects/', $parameters);

        $json = json_decode($response->getBody());

        $projs = $this->mapper->mapArray(
            $json, collect(), GitlabDto::class
        );

fetch_next:
        $next = $this->hasNext($response);
        if ($next != null) {
            $response = $this->client->request('projects/', $parameters);

            $tmp = $this->mapper->mapArray(
                $json, collect(), GitlabDto::class
            );
            $projs = $projs->merge($tmp);
        }

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

    /**
     * @param $response
     * @return string next url
     */
    private function hasNext($response) : ?string
    {
        // find Link Header for remained data
        $link = $response->getHeader('Link');

        // no more data
        if (empty($link))
            return null;

        $ar = preg_split("/,/", $link[0]);

        $found = false;
        $next = null;

        foreach($ar as $l) {
            // format: <https://gitlab.example.com/api/v3/projects?page=2&per_page=100>; rel="next"
            if (preg_match('/<(.*)>;[ \t]*rel="next"/', $l, $next) === 1) {
                return $next[1];
            }
        }

        return $next;
    }
}
