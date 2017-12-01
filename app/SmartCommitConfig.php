<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Transition implements \JsonSerializable {
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
    private $transition;

    private $file = 'settings.json';

    public function __construct()
    {
        $this->transition = new Transition();
    }

    public function load($file = 'settings.json')
    {
        $string = file_get_contents(base_path() . DIRECTORY_SEPARATOR  . $file);

        $this->transition = json_decode($string);

        dd($this->transition);
    }

    /**
     * save settings to file.
     *
     * @param bool $overwrite overwrite previous settings.json
     *
     */
    public function save($overwrite = false)
    {
        $json = json_encode($this->transition, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

        //dump($json);
        if (Storage::exists($this->file) && $overwrite !== true) {
            $now = Carbon::now();
            $now->setToStringFormat('Y-m-d-H-i-s');
            Storage::move($this->file, $this->file . '-' . $now);
        }

        Storage::put($this->file, $json);
    }
}