<?php

namespace Tests\Unit;

use App\SmartCommitConfig;
use Tests\TestCase;
use App\Commands\HelloCommand;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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
