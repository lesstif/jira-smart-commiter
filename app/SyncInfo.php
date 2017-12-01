<?php
namespace App;

class Project {
    private $jira;
    private $gitlab;
}

class SyncInfo implements \JsonSerializable
{
    private $projects = [];

    public function __construct()
    {
        $this->projects = new Project();
    }

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