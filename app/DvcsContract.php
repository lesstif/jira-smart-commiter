<?php
namespace App;

abstract class DvcsContract
{
    protected $config;

    protected $debug;
    protected $verbose;

    public function __construct(SmartCommitConfig $config)
    {
        $this->$config = $config;
    }

    public function envLoad()
    {
        //$this->config = new SmartCommitConfig();
        //$this->config->load($file);

        /*
        // gitlab login info
        $this->gitHost  = str_replace("\"", "", getenv('GITLAB_HOST'));
        $this->gitToken = str_replace("\"", "", getenv('GITLAB_TOKEN'));

        $this->jiraHost = str_replace("\"", "", getenv('JIRA_HOST'));
        $this->jiraUser = str_replace("\"", "", getenv('JIRA_USER'));
        $this->jiraPasswd = str_replace("\"", "", getenv('JIRA_PASS'));
*/
        $this->debug = str_replace("\"", "", getenv('APP_DEBUG'));
        if (strtolower($this->debug) === 'true') {
            $this->debug = true;
        }
        $this->verbose = str_replace("\"", "", getenv('APP_VERBOSE'));

        if (strtolower($this->verbose) === 'true') {
            $this->verbose = true;
        }
    }

    public function __get($name)
    {
        echo "Getting '$name'\n";

        $data = get_object_vars($this->config);

        if (array_key_exists($name, $data)) {
            return $this->data[$name];
        }

        return null;
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
