<?php
namespace App;

use Illuminate\Support\Facades\Log;

abstract class DvcsContract
{
    protected $config;

    protected $debug;
    protected $verbose;

    protected $data;

    public function __construct(SmartCommitConfig $config)
    {
        $this->config = $config;
    }

    protected function getProperty($name)
    {
        //Log::debug("Getting '$name'");

        if(empty($this->data)) {
            $this->data = $this->config->getSettings()->jsonSerialize();
        }

        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
    }

    /**
     * List all Projects
     *
     * @return mixed
     */
    abstract public function getProjects() : array ;

    abstract public function getProjectInfo($projectId) : array ;

    abstract public function getCommits($projectId, $since, $until, $options) : array ;
}
