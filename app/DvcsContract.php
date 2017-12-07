<?php
namespace App;

use Illuminate\Support\Facades\Log;

abstract class DvcsContract
{
    /** @var SmartCommitConfig  */
    protected $config;

    protected $debug;
    protected $verbose;

    /** @var array */
    protected $data;

    /** @var \JsonMapper */
    protected $mapper;

    public function __construct(SmartCommitConfig $config)
    {
        $this->config = $config;
        $this->mapper = new \JsonMapper();
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

    /**
     * save DVCS Project Info
     *
     * @param $projects
     * @return mixed
     */
    abstract public function saveProjects($projects) : void;
}
