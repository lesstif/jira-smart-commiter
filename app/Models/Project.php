<?php
namespace App\Models;

class Gitlab
{
    public $id;
    public $description;
    public $name;
    public $web_url;
}

class Jira
{
    public $id;
    public $name;
    public $url;
}

class Project
{
    /**
     * @var string dvscs type
     */
    public $dvcsType;

    /**
     * @var Gitlab;
     */
    public $gitlab;

    /**
     * @var null;
     */
    public $github;

    /**
     * @var null
     */
    public $bitBucket;

    /**
     * @var Jira
     */
    public $jira;
}