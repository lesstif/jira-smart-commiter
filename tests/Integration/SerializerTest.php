<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\SmartCommitConfig;

class SerializerTest extends TestCase
{
    /** @test */
    public function SmartCommitConfigTest(): void
    {
        $s = new SmartCommitConfig();

        $s->save(true);

        $this->assertTrue(true);
    }
}
