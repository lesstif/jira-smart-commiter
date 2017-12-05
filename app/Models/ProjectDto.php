<?php
namespace App\Models;

class ProjectDto
{
    /**
     * @var string dvscs type
     */
    public $dvcsType;

    /**
     * @var GitlabDto;
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
     * @var JiraDto
     */
    public $jira;


    public function __construct($jira = null, $gitlab = null, $github = null, $bitBucket = null)
    {
        $this->jira = $jira;
        $this->gitlab = $gitlab;
        $this->github = $github;
        $this->bitBucket = $bitBucket;
    }

}