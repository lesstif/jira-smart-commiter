<?php
namespace App\Models;

class GitlabDto
{
    /**
     * @var integer
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

    /** @var UserDto */
    public $owner;

    /** @var string commit hash */
    public $lastCommit;
}
