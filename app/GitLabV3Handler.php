<?php

namespace App;

use App\Models\CommitDto;
use App\Models\GitlabDto;
use App\Models\ProjectDto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use LINK_HEADER;

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
     * @param integer $projectId
     * @param null $since
     * @param null $until
     * @param array $options
     * @return \Illuminate\Support\Collection
     * @throws Exceptions\SmartCommitException
     */
    public function getCommits(int $projectId, Carbon $since = null, Carbon $until = null, string $branch = null): \Illuminate\Support\Collection
    {
        $url = sprintf('projects/%d/repository/commits', $projectId);

        $url .= '?' . http_build_query([
               'since' => toIso8601String($since),
               'until' => toIso8601String($until),
               'ref_name' => $branch,
            ],
PHP_QUERY_RFC3986);

        debug('request commit list:', $url);

        $response = $this->client->request($url);

        $commits = $this->mapper->mapArray(
            json_decode($response->getBody()), collect(), CommitDto::class
        );

        $next_url = '';

        while (has_next_link($response, $next_url) == LINK_HEADER::HAS_NEXT()) {
            debug('fetch next commit data.', $next_url);
            $gitlabHost = $this->config->getProperty('gitlabHost');
            $gitlabToken = $this->config->getProperty('gitlabToken');

            $htc = new HttpClient($gitlabHost, $gitlabToken);

            $response = $htc->requestNoParam($next_url);

            $tmp = $this->mapper->mapArray(
                json_decode($response->getBody()), collect(), CommitDto::class
            );
            $commits = $commits->merge($tmp);

            debug('Fetched '.count($tmp).',Total:', count($commits));
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

        $next_url = '';
        while (has_next_link($response,$next_url) == LINK_HEADER::HAS_NEXT()) {
            debug("fetch next data.", $next_url);
            $gitlabHost = $this->config->getProperty('gitlabHost');
            $gitlabToken = $this->config->getProperty('gitlabToken');

            $htc = new HttpClient($gitlabHost, $gitlabToken);

            $response = $htc->requestNoParam($next_url);

            $tmp = $this->mapper->mapArray(
                json_decode($response->getBody()), collect(), GitlabDto::class
            );
            $projs = $projs->merge($tmp);

            debug("Fetched ".count($tmp).",Total:", count($projs));
        }

        $projs->transform(function ($item, $key) {
            $item->setDvcs('gitlab', 'V3');

            return $item;
        });

        return $projs;
    }
}
