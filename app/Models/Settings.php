<?php
namespace App\Models;

/**
 * Class Settings
 * @package App
 */
class Settings implements \JsonSerializable {
    public $jiraHost = 'https://you-jira.host.com';
    public $jiraUser = 'jira-username';
    public $jiraPass = 'jira-password';

    public $dvcsType = 'gitlab';

    /**
     * @var string gitlab API Version, V3|V4
     */
    public $gitlabApiVersion = 'V4';

    public $gitlabHost = 'https://your-gitlab.host.com';
    public $gitlabToken = 'gitlab-token-here';

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

    function __construct()
    {

    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}