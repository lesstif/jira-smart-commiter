<?php

namespace App\Models;

class GitlabDto extends ProjectDto
{
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

    public function __construct($jira, $apiVersion = 'V4')
    {
        parent::__construct($jira, $apiVersion);

        $this->dvcsType = 'gitlab';
    }
}
