<?php

declare(strict_types=1);

namespace SFW2\Config;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    public static function valid__constructDataProvider(): array {
        return [
            [['abc' => []]]
        ];
    }

    #[DataProvider('valid__constructDataProvider')]
    public function testValid__construct(array $data): void
    {
        $obj = new Config($data);
        $this->assertInstanceOf(Config::class, $obj);
        $this->assertEquals($data, $obj->getAsArray());
    }

    public static function getAndHasDataProvider(): array
    {
        return [
            [['abc' => ['a' => 1]], 'abc', ['a' => 1]],
            [['abc' => ['a' => 1]], 'abc.a', 1],
            [['abc' => ['a' => []]], 'abc', ['a' => []]],
            [['abc' => ['a' => []]], 'abc.a',  []],
            [self::getData(), 'site.offline', false],
            [self::getData(), 'pathes.templates', ['SFW2\Boilerplate' =>  '../templates/']],
        ];
    }

    #[DataProvider('getAndHasDataProvider')]
    public function testGetAndHas(array $data, string $key, mixed $value): void
    {
        $obj = new Config($data);
        $this->assertTrue($obj->has($key));
        $this->assertEquals($value, $obj->get($key));
    }


    public static function getData(): array
    {
        return [
            'database' => [
                'dsn' =>  'sqlite:/home/stefan/Documents/projects/php/sfw2/sfw2-boilerplate/data/sfw2.sqlite',
                'user' =>  'hp_envy_laptop',
                'pwd' =>  'password1',
                'options' => [
                ],
                'prefix' => 'sfw2'
            ],
            'site' => [
                'offline' =>  false,
                'debugMode' =>  true,
                'offlineBypassToken' =>  'd19e518c3f2ff2a9a22a1c66d78dcab9'
            ],
            'project' => [
                'title' =>  'Blaha',
                'eMailWebMaster' =>  'webmaster@localhost'
            ],
            'defEMailAddr' => [
                'addr' =>  'noreply@localhost',
                'name' =>  'noreply'
            ],
            'misc' => [
                'timeZone' =>  'Europe/Berlin',
                'locale' =>  'de_DE' ,
                'memoryLimit' =>  '256M'
            ],
            'pathes' => [
                'tmp' =>  'tmp/' ,
                'log' =>  'weblog/',
                'data' =>  'data/' ,
                'templates' => [
                    'SFW2\Boilerplate' =>  '../templates/'
                ]
            ]
        ];
    }
}
