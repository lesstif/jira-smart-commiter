<?php

namespace Tests\Unit;

use Tests\TestCase;

class UtilCommandTest extends TestCase
{
    /** @test */
    public function SmartCommitConfigTest(): void
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function linkHeaderParse(): void
    {
        //$url = '<https://gitlab.ktnet.com/api/v3/projects?page=2&per_page=29>; rel="next", <https://gitlab.ktnet.com/api/v3/projects?page=1&per_page=29>; rel="first", <https://gitlab.ktnet.com/api/v3/projects?page=7&per_page=29>; rel="last"';
        $url = '<https://gitlab.ktnet.com/api/v3/projects?page=1&per_page=29>; rel="prev", <https://gitlab.ktnet.com/api/v3/projects?page=4&per_page=29>; rel="next", <https://gitlab.ktnet.com/api/v3/projects?page=1&per_page=29>; rel="first", <https://gitlab.ktnet.com/api/v3/projects?page=7&per_page=29>; rel="last"';

        $ar = preg_split('/,/', $url);

        $found = false;
        $next = null;

        foreach ($ar as $l) {
            // format: <https://gitlab.example.com/api/v3/projects?page=2&per_page=100>; rel="next"
            if (preg_match('/<(.*)>;[ \t]*rel="next"/', $l, $next) === 1) {
                echo 'next url:'.$next[1]."\n";
                //return $next[1];
            }
        }

        $this->assertTrue(true);
    }
}
