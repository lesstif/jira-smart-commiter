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

    public function __construct()
    {
        $this->mapper = new \JsonMapper();

        $this->config = new SmartCommitConfig;

        $this->config->loadSettings();
    }

    public function setDvcsTypeAndVersion($dvcsType, $apiVersion)
    {
        $this->config->setDvcsTypeAndVersion($dvcsType, $apiVersion);
    }

    /**
     * @param array $options
     * @return \Illuminate\Support\Collection
     */
    abstract public function getProjects($options = []) : \Illuminate\Support\Collection;

    /**
     * List all Projects.
     *
     * @return \Illuminate\Support\Collection
     */
    abstract public function getAllProjects($options = []) : \Illuminate\Support\Collection;

    /**
     * @param $projectId
     * @return \Illuminate\Support\Collection
     */
    abstract public function getProjectInfo($projectId) : \Illuminate\Support\Collection;

    /**
     * @param $projectId
     * @param $since
     * @param $until
     * @param array $options
     * @return \Illuminate\Support\Collection
     */
    abstract public function getCommits($projectId, $since, $until, $options = []) : \Illuminate\Support\Collection;

    /**
     * save DVCS Project Info.
     *
     * @param $projects
     * @return mixed
     */
    public function saveProjects($projects, $file = 'projects.json') : void
    {
        Log::info('saveProjects : '.count($projects));

        // loading project list
        $prevProjs = [];

        if (Storage::exists($file)) {
            $json = Storage::get($file);

            $prevProjs = json_decode($json);
        }

        Log::debug("Before Project count:".count($prevProjs));

        foreach ($projects as $p) {
            foreach ($prevProjs as $idx=>$value) {
                if ($value->id === $p->id) {
                    Log::debug("$p->id $p->name is already exist. replacing it. $p->dvcsType");
                    $prevProjs[$idx] = $p;
                    break;
                }
            }
            $prevProjs[] = $p;
        }
        Log::debug("After Project count:".count($prevProjs));

        // replace
        $json = json_encode($prevProjs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        Storage::put($file, $json);
    }
}
