<?php

namespace Tests\Unit;

use App\SmartCommitConfig;
use Tests\TestCase;
use App\Commands\HelloCommand;

class UtilCommandTest extends TestCase
{
    /** @test */
    public function SmartCommitConfigTest(): void
    {
        $s = new SmartCommitConfig();

        $s->save(true);

        $this->assertTrue(true);
    }
}
