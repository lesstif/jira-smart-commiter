<?php

namespace Tests\Unit;

use App\GitLabHandler;
use Symfony\Component\VarDumper\VarDumper;
use Tests\TestCase;
use App\Commands\HelloCommand;

class GitLabCommandTest extends TestCase
{
    /** @test */
    public function project_all(): array
    {
        $gitlab = new GitLabHandler();

        $parameters = ['owned' => true, 'per_page' => 100];

        $projects = $gitlab->getProjects($parameters);

        foreach ($projects as $p) {
       //     VarDumper::dump($p['web_url']);
        }

        VarDumper::dump(['count' => count($projects)]);

        $this->assertTrue(true);

        return $projects;
    }

    /**
     * @test
     * @depends project_all
     */
    public function project_info($projects): array
    {
        $gitlab = new GitLabHandler();

        foreach ($projects as $p) {
            $proj = $gitlab->getProjectInfo($p['id']);

            VarDumper::dump($proj);

            break;
        }

        $this->assertTrue(true);

        return $projects;
    }

    /**
     * @test
     * @depends project_info
     */
    public function commits_info($projects): void
    {
        $gitlab = new GitLabHandler();

        $idx = 0;
        foreach ($projects as $p) {
            $commits = $gitlab->getCommits($p['id']);

            VarDumper::dump($commits);

            if ($idx++ > 10)
                break;
        }

        $this->assertTrue(true);
    }
}
