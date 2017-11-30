<?php
namespace App;

abstract class DvcsContract
{
    /**
     * @var \Dotenv\Dotenv
     */
    protected $dotenv;

    /** @var string  */
    protected $gitHost;

    /** @var string  */
    protected $gitToken;

    /** @var string  */
    protected $jiraHost;

    /** @var string  */
    protected $jiraUser;

    /** @var string  */
    protected $jiraPasswd;

    protected $debug = false;
    protected $verbose = false;

    public function envLoad($path = null)
    {
        if (empty($path))
            $path = base_path();

        $dotenv = new \Dotenv\Dotenv($path);
        $dotenv->load();

        // gitlab login info
        $this->gitHost  = str_replace("\"", "", getenv('GITLAB_HOST'));
        $this->gitToken = str_replace("\"", "", getenv('GITLAB_TOKEN'));

        $this->jiraHost = str_replace("\"", "", getenv('JIRA_HOST'));
        $this->jiraUser = str_replace("\"", "", getenv('JIRA_USER'));
        $this->jiraPasswd = str_replace("\"", "", getenv('JIRA_PASS'));

        $debug = str_replace("\"", "", getenv('APP_DEBUG'));
        if (strtolower($debug) === 'true') {
            $this->debug = true;
        }
        $verbose = str_replace("\"", "", getenv('APP_VERBOSE'));

        if (strtolower($verbose) === 'true') {
            $this->verbose = true;
        }
    }

    public function isDebug()
    {
        return $this->debug;
    }

    public function isVerbose()
    {
        return $this->verbose;
    }

    /**
     * List all Projects
     *
     * @return mixed
     */
    abstract public function getProjects() : array ;

    abstract public function getProjectInfo($projectId) : array ;

    abstract public function getCommits($projectId, $since, $until, $options) : array ;
}