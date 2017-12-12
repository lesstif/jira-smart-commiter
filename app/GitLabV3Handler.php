<?php

namespace App;

use App\Models\CommitDto;
use App\Models\GitlabDto;
use Illuminate\Support\Facades\Log;

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

        $response = $this->client->request('projects/', $parameters);

        $projs = $this->mapper->mapArray(
            json_decode($response->getBody()), collect(), GitlabDto::class
        );

        // add property
        $projs->transform(function ($item, $key) {
            $item->setDvcs('gitlab', 'V3');

            return $item;
        });

        return $projs;
    }

    /**
     * @param $projectId
     * @param null $since
     * @param null $until
     * @param array $options
     * @return \Illuminate\Support\Collection
     * @throws Exceptions\SmartCommitException
     */
    public function getCommits($projectId, $since = null, $until = null, $options = []): \Illuminate\Support\Collection
    {
        $url = sprintf('projects/%d/repository/commits', $projectId);
        $response = $this->client->request($url, $options);

        $commits = $this->mapper->mapArray(
            json_decode($response->getBody()), collect(), CommitDto::class
        );

        while (($next = $this->hasNext($response)) != null) {
            Log::debug("fetch next commit data..$next\n");
            $gitlabHost = $this->config->getProperty('gitlabHost');
            $gitlabToken = $this->config->getProperty('gitlabToken');

            $htc = new HttpClient($gitlabHost, $gitlabToken);

            $response = $htc->requestNoParam($next);

            $tmp = $this->mapper->mapArray(
                json_decode($response->getBody()), collect(), CommitDto::class
            );
            $commits = $commits->merge($tmp);

            Log::debug('Fetched '.count($tmp).',Total:'.count($commits));
        }

        return $commits;
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

        $projs = $this->mapper->mapArray(
            json_decode($response->getBody()), collect(), GitlabDto::class
        );

        while (($next = $this->hasNext($response)) != null) {
            //Log::debug("fetch next data..$next\n");
            $gitlabHost = $this->config->getProperty('gitlabHost');
            $gitlabToken = $this->config->getProperty('gitlabToken');

            $htc = new HttpClient($gitlabHost, $gitlabToken);

            $response = $htc->requestNoParam($next);

            $tmp = $this->mapper->mapArray(
                json_decode($response->getBody()), collect(), GitlabDto::class
            );
            $projs = $projs->merge($tmp);

            //Log::debug("Fetched ".count($tmp).",Total:".count($projs));
        }

        $projs->transform(function ($item, $key) {
            $item->setDvcs('gitlab', 'V3');

            return $item;
        });

        return $projs;
    }

    /**
     * gitlab api v3 pagination processing.
     *
     * @param $response
     * @return string|null next url
     */
    private function hasNext($response) : ?string
    {
        // find Link Header for remained data
        $link = $response->getHeader('Link');

        // no more data
        if (empty($link)) {
            debug('Link header is not exist!', $link);

            return null;
        }

        $ar = preg_split('/,/', $link[0]);

        $found = false;
        $next = null;

        foreach ($ar as $l) {
            // format: <https://gitlab.example.com/api/v3/projects?page=2&per_page=100>; rel="next"
            //Link: <https://api.github.com/resource?page=2>; rel="next",
            //<https://api.github.com/resource?page=5>; rel="last"
            if (preg_match('/<(.*)>;[ \t]*rel="next"/', $l, $next) === 1) {
                return $next[1];
            }
        }
        info('we reached last entity! ', $ar);

        return null;
    }
}
