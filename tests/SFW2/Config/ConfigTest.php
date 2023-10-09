<?php

namespace SFW2\Config;

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{

    public function testConfig()
    {
        $obj = new Config(['abc' => 'def']);
        static::assertTrue($obj->has('abc'));
        static::assertEquals('def', $obj->get('abc'));
    }


    public function testHas()
    {

    }

    public function testGetAsArray()
    {

    }

    public function testGet()
    {

    }

    public function test__construct()
    {

    }
}
