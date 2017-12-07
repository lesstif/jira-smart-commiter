<?php
namespace App\Models;

abstract class ProjectDto
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

    public function __construct($jira, $apiVersion = 'V4')
    {
        $this->jira = $jira;
        $this->apiVersion = $apiVersion;
    }

}