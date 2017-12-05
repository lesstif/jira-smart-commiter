<?php
namespace App;

use App\Exceptions\SmartCommitException;
use App\Models\Settings;
use Carbon\Carbon;
use Gitlab\Model\Project;
use Illuminate\Support\Facades\Storage;
use JsonMapper;

/**
 * Model class
 *
 * Class SmartCommitConfig
 */
class SmartCommitConfig
{
    /**
     * @var Settings
     */
    private $settings;

    /** @var array */
    private $projects;

    private $data;

    public function __construct()
    {
        $this->settings = new Settings();
    }

    /**
     *
     * @param $name
     * @return null
     */
    public function getProperty($name)
    {
        echo "Getting '$name'\n";

        $this->data = $this->settings->jsonSerialize();

        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
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
        if (!Storage::exists($file)) {
            throw new SmartCommitException("Config file " . $file . " Not found. running 'php jira-smart-config init' ");
        }

        $json = Storage::get($file);

        $mapper = new JsonMapper();

        $this->settings = $mapper->map(json_decode($json), new Settings());
    }

    /**
     * save settings to file.
     *
     * @param bool $overwrite overwrite previous settings.json
     *
     */
    public function saveSettings($file = 'settings.json', $overwrite = false)
    {
        $json = json_encode($this->settings, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        if (Storage::exists($file) && $overwrite !== true) {
            $now = Carbon::now();
            $now->setToStringFormat('Y-m-d-H-i-s');
            Storage::move($file, $file . '-' . $now);
        }

        Storage::put($file, $json);
    }

    /**
     * load DVCS Project list from json
     *
     * @param string $file
     * @throws SmartCommitException
     * @throws \JsonMapper_Exception
     */
    public function loadProjects($file = 'projects.json')
    {
        if (!Storage::exists($file)) {
            throw new SmartCommitException("Config file " . $file . " Not found. running 'php jira-smart-config init' ");
        }

        $json = Storage::get($file);

        $mapper = new JsonMapper();

        $this->projects = $mapper->mapArray(
            json_decode($json),
            array(),
            new Project()
        );
    }

    public function saveProjects($file = 'projects.json', $overwrite = false)
    {
        $json = json_encode($this->projects, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        if (Storage::exists($file) && $overwrite !== true) {
            $now = Carbon::now();
            $now->setToStringFormat('Y-m-d-H-i-s');
            Storage::move($file, $file . '-' . $now);
        }

        Storage::put($file, $json);

    }
}