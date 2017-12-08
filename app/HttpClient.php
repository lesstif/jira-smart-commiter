<?php

namespace App;

use App\Exceptions\SmartCommitException;
use Illuminate\Support\Facades\Log;

class HttpClient
{
    /**
     * default option array.
     * @var array
     */
    protected $options = [];

    /**
     * JIRA REST API URI.
     *
     * @var string
     */
    protected $API_VERSION = '/api/v3/';

    /**
     * Json Mapper.
     *
     * @var \JsonMapper
     */
    protected $json_mapper;

    private $gitLabHost;
    private $gitLabToken;

    private $defaultConfig;

    public function __construct($gitLabHost, $gitLabToken)
    {
        $this->gitLabHost = $gitLabHost;
        $this->gitLabToken = $gitLabToken;

        // setting http prop
        $this->defaultConfig['timeout'] = 100;
        $this->defaultConfig['verify'] = false;
    }

    /**
     * performing gitlab api request.
     *
     * @param $uri API uri
     * @return string json string
     */
    public function request($uri, $queryParam = [], $option = []) : \GuzzleHttp\Psr7\Response
    {
        $basket = array_replace($this->defaultConfig, $option, ['base_uri' => $this->gitLabHost]);

        $client = new \GuzzleHttp\Client($basket);

        $response = $client->get($this->gitLabHost.$this->API_VERSION.$uri, [
            // TODO $queryParam process
            'query' => [
                'page' => $option['page'] ?? 1,
                'per_page' => $option['per_page'] ?? 50,
            ],
            'headers' => [
                'PRIVATE-TOKEN' => $this->gitLabToken,
            ],
        ]);

        // TODO add 20X status
        if ($response->getStatusCode() != 200) {
            throw new SmartCommitException('Http request failed. status code : '
                .$response->getStatusCode().' reason:'.$response->getReasonPhrase());
        }

        //return json_decode($response->getBody());
        return $response;
    }

    public function requestNoParam($full_url) : \GuzzleHttp\Psr7\Response
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->get($full_url, [
            'headers' => [
                'PRIVATE-TOKEN' => $this->gitLabToken,
            ],
        ]);

        // TODO add 20X status
        if ($response->getStatusCode() != 200) {
            throw new SmartCommitException('Http request failed. status code : '
                .$response->getStatusCode().' reason:'.$response->getReasonPhrase());
        }

        return $response;
    }


    /**
     * performing gitlab api request.
     *
     * @param $uri API uri
     * @param $body body data
     *
     * @return type json response
     */
    public function send($uri, $body, $method = 'POST', $option = [])
    {
        $basket = array_replace($this->defaultConfig, $option, ['base_uri' => $this->gitLabHost]);

        $client = new \GuzzleHttp\Client($basket);

        $postData['headers'] = ['PRIVATE-TOKEN' => $this->gitToken];

        $postData['json'] = $body;

        if ($this->debug) {
            $postData['debug'] = fopen(base_path().'/'.'debug.txt', 'w');
        }

        $request = new \GuzzleHttp\Psr7\Request($method, $this->gitHost.$this->API_VERSION.$uri);

        try {
            $response = $client->send($request, $postData);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error("Error : " . json_encode($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error($e->getResponse());
            }
        }

        if ($response->getStatusCode() != 200 && $response->getStatusCode() != 201) {
            throw new JiraIntegrationException('Http request failed. status code : '
                .$response->getStatusCode().' reason:'.$response->getReasonPhrase());
        }

        return json_decode($response->getBody());
    }
}
