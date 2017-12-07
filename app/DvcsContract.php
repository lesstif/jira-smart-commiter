<?php

namespace App;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

abstract class DvcsContract
{
    /** @var SmartCommitConfig */
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

        if (empty($this->data)) {
            $c = $this->config;
            $this->data = $c->jsonData();
        }

        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
    }

    /**
     * @param array $options
     * @return \Illuminate\Support\Collection
     */
    abstract public function getProjects($options = []) : \Illuminate\Support\Collection ;

    /**
     * List all Projects.
     *
     * @return \Illuminate\Support\Collection
     */
    abstract public function getAllProjects($options = []) : \Illuminate\Support\Collection ;

    /**
     * @param $projectId
     * @return \Illuminate\Support\Collection
     */
    abstract public function getProjectInfo($projectId) : \Illuminate\Support\Collection ;

    /**
     * @param $projectId
     * @param $since
     * @param $until
     * @param array $options
     * @return \Illuminate\Support\Collection
     */
    abstract public function getCommits($projectId, $since, $until, $options = []) : \Illuminate\Support\Collection ;

    /**
     * save DVCS Project Info.
     *
     * @param $projects
     * @return mixed
     */
    public function saveProjects($projects, $file = 'projects.json') : void
    {
        Log::info('saveProjects : ' . json_encode($projects));

        // loading project list
        $prevProjs = [];

        if (Storage::exists($file)) {
            $json = Storage::get($file);

            $prevProjs = json_decode($json);
        }

        foreach ($projects as $p) {

            foreach($prevProjs as $idx=>$value) {
                if ($value->id === $p->id) {
                    Log::debug("$p->id $p->name is already exist. replacing it. $p->dvcsType");
                    $prevProjs[$idx] = $p;
                    break;
                }
            }
            $prevProjs[] = $p;
        }

        // replace
        $json = json_encode($prevProjs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        Storage::put($file, $json);
    }
}
