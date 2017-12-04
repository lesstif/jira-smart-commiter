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

    public function getProperty($name)
    {
        //Log::debug("Getting '$name'");

        if(empty($this->data)) {
            $c = $this->config;
            $this->data = $c->jsonData();
        }

        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
    }

    abstract public function getProjects($options = []) : array ;

    /**
     * List all Projects
     *
     * @return mixed
     */
    abstract public function getAllProjects($options = []) : array ;

    abstract public function getProjectInfo($projectId) : array ;

    abstract public function getCommits($projectId, $since, $until, $options = []) : array ;
}
