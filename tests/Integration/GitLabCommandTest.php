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

        $this->assertTrue(false);

        return $projects;
    }

    /**
     * @test
     * @depends project_all
     */
    public function project_info($projects): void
    {
        $this->assertTrue(false);

        $gitlab = new GitLabHandler();

        foreach ($projects as $p) {
            VarDumper::dump($p['web_url']);
        }
    }
}
