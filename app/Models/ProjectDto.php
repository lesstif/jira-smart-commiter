<?php

namespace App\Models;

abstract class ProjectDto implements \JsonSerializable
{
    /**
     * @var string dvscs type
     */
    public $dvcsType;

    /** @var string|null */
    public $apiVersion;

    /**
     * @var JiraDto
     */
    public $jira;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string|null
     */
    public $description;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $web_url;

    /** @var string|null */
    public $path_with_namespace;

    /** @var UserDto */
    public $owner;

    /** @var string commit hash */
    public $lastCommit;

    /** @var string last commit date time */
    public $lastCommitDateTime;

    abstract function __construct();

    public function setDvcs($dvcsType, $apiVersion)
    {
        $this->dvcsType = $dvcsType;
        $this->apiVersion = $apiVersion;
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}
