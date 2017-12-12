<?php

namespace App;

use JsonMapper;
use App\Models\Settings;
use Gitlab\Model\Project;
use App\Models\ProjectDto;
use App\Exceptions\SmartCommitException;

/**
 * Model class.
 *
 * Class SmartCommitConfig
 */
class SmartCommitConfig
{
    /**
     * @var Settings
     */
    private $settings;

    private $data;

    public function __construct()
    {
        $this->settings = new Settings();
    }

    public function setDvcsTypeAndVersion($dvcsType, $apiVersion)
    {
        $this->settings->dvcsType = $dvcsType;
        $this->settings->gitlabApiVersion = $apiVersion;
    }

    /**
     * @param $name
     * @return null
     */
    public function getProperty($name)
    {
        $this->data = $this->settings->jsonSerialize();

        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
    }

    public function getSettings() : Settings
    {
        return $this->settings;
    }

    public function jsonData()
    {
        return $this->settings->jsonSerialize();
    }

    public function loadSettings($file = 'settings.json')
    {
        if (! is_config_exists($file)) {
            throw new SmartCommitException('Config file '.$file." Not found. running 'php jira-smart-config init' ");
        }

        $json = config_load($file);

        $mapper = new JsonMapper();

        $this->settings = $mapper->map(json_decode($json), new Settings());
    }

    /**
     * save settings to file.
     *
     * @param bool $overwrite overwrite previous settings.json
     */
    public function saveSettings($file = 'settings.json', $overwrite = false)
    {
        $json = json_encode($this->settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        config_save($json, $file, $overwrite);
    }

    /**
     * load DVCS Project list from json.
     *
     * @param bool $throwException
     * @param string $file
     * @throws SmartCommitException
     * @throws \JsonMapper_Exception
     */
    public static function loadProjects($throwException, $file = 'projects.json') :  \Illuminate\Support\Collection
    {
        if (! is_config_exists($file)) {
            if ($throwException === true) {
                throw new SmartCommitException('Project Setting file '.$file." Not found. running 'php jira-smart-config project:create-list' ");
            } else {
                return collect();
            }
        }

        $json = config_load($file);

        $mapper = new JsonMapper();

        $projects = $mapper->mapArray(
            json_decode($json),
            collect(),
            ProjectDto::class
        );

        return $projects;
    }

    public function saveProjects($file = 'projects.json', $overwrite = false)
    {
        $json = json_encode($this->projects, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        config_save($json, $file, $overwrite);
    }
}
