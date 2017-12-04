<?php
namespace App;

use App\Exceptions\SmartCommitException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Settings implements \JsonSerializable {
    public $jiraHost = "https://you-jira.host.com";
    public $jiraUser = "jira-username";
    public $jiraPass = "jira-password";

    public $dvcsType = "gitlab";

    public $gitlabHost = "https://your-gitlab.host.com";
    public $gitlabToken = "gitlab-token-here";

    public $transitions_comment = '{USER} Issue {TRANSITION} with {COMMIT}';
    public $transitions = [
        [
            'name' => "Resolved",
            'keywords' => [
                'resolve',
                'fix',
             ],
        ],
        [
            'name' => "Closed",
            'keywords' => [
                'close',
                '닫음',
            ],
        ],
    ];

    public $referencing_comment = '{USER} mentioned this issue in {COMMIT}';
    public $referencing = [
            'ref',
            '참조',
    ];

    public $merging_comment = '{USER} {COMMIT_MESSAGE} with  issue in {COMMIT}';
    public $merging = [
            'merge',
        ];

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}

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
    public function __get($name)
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

    public function load($file = 'settings.json')
    {
        if (!Storage::exists($file)) {
            throw new SmartCommitException("Config file " . $file . " Not found. running 'php jira-smart-config init' ");
        }

        $json = Storage::get($file);

        //$string = file_get_contents(base_path() . DIRECTORY_SEPARATOR  . $file);

        $this->settings = json_decode($json);
    }

    /**
     * save settings to file.
     *
     * @param bool $overwrite overwrite previous settings.json
     *
     */
    public function save($file = 'settings.json', $overwrite = false)
    {
        $json = json_encode($this->settings, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        if (Storage::exists($file) && $overwrite !== true) {
            $now = Carbon::now();
            $now->setToStringFormat('Y-m-d-H-i-s');
            Storage::move($file, $file . '-' . $now);
        }

        Storage::put($file, $json);
    }
}