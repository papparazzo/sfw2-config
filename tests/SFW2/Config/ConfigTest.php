<?php

declare(strict_types=1);

namespace SFW2\Config;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SFW2\Config\Exceptions\ContainerException;
use SFW2\Config\Exceptions\NotFoundException;

final class ConfigTest extends TestCase
{
    public static function valid__constructDataProvider(): array {
        return [
            [['abc' => []]],
            [[]],
            [['abc' => ['a' => 1]]]
        ];
    }

    /**
     * @throws ContainerException
     */
    #[DataProvider('valid__constructDataProvider')]
    public function testValid__construct(array $data): void
    {
        $obj = new Config($data);
        ConfigTest::assertInstanceOf(Config::class, $obj);
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
            [self::getData(), 'misc.memoryLimit', '256M']
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ContainerException
     */
    #[DataProvider('getAndHasDataProvider')]
    public function testGetAndHas(array $data, string $key, mixed $value): void
    {
        $obj = new Config($data);
        ConfigTest::assertTrue($obj->has($key));
        ConfigTest::assertSame($value, $obj->get($key));
    }

    public static function getGetAsArrayDataProvider(): array
    {
        return [
            [['abc' => ['a' => 1]], ['abc' => ['a' => 1], 'abc.a' => 1]],
        ];
    }

    /**
     * @throws ContainerException
     */
    #[DataProvider('getGetAsArrayDataProvider')]
    public static function testGetAsArray(array $data, array $result): void
    {
        $obj = new Config($data);
        ConfigTest::assertSame($result, $obj->getAsArray());
    }

    /**
     * @throws ContainerException
     * @throws ContainerExceptionInterface
     */
    public function testNotFound(): void
    {
        $this->expectException(NotFoundException::class);
        $obj = new Config([]);
        $obj->get('does_not_exist');
    }

    public static function getInvalidDataDataProvider(): array
    {
        return [
            [['111' => 'abc']],
            [[111 => []]],
        ];
    }

    #[DataProvider('getInvalidDataDataProvider')]
    public function testExceptionOnConstruct(array $data): void
    {
        $this->expectException(ContainerException::class);
        new Config($data);
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
