<?php

declare(strict_types=1);

namespace Era269\MethodMap\Tests;

use DateTime;
use Era269\MethodMap\MethodMapCacheDecorator;
use Era269\MethodMap\MethodMapInterface;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

class MethodMapCacheDecoratorTest extends TestCase
{
    private const NOT_CACHED_METHOD = 'method';
    private const CACHED_METHOD     = 'cached';
    private const KEY_PREFIX        = 'key-prefix';

    /**
     * @dataProvider dataProvider
     *
     * @param string[]|null $cachedMethodNames
     * @param string[] $notCachedMethodNames
     * @param string[] $expectedMethodNames
     */
    public function testGetMethodNames(?array $cachedMethodNames, array $notCachedMethodNames, array $expectedMethodNames): void
    {
        $methodMap = $this->createMock(MethodMapInterface::class);
        $methodMap
            ->method('getMethodNames')
            ->willReturn($notCachedMethodNames);
        $cache = $this->createMock(CacheInterface::class);
        $cache
            ->method('get')
            ->willReturn($cachedMethodNames);

        if (null === $cachedMethodNames) {
            $cache
                ->expects($this->once())
                ->method('set')
                ->with(
                    md5(self::KEY_PREFIX . '-' . DateTime::class),
                    $notCachedMethodNames
                );
        }
        $methodMapCacheDecorator = new MethodMapCacheDecorator($methodMap, $cache, self::KEY_PREFIX);
        self::assertEquals(
            $expectedMethodNames,
            $methodMapCacheDecorator->getMethodNames(new DateTime())
        );
    }

    /**
     * @return array[]
     */
    public function dataProvider(): array
    {
        return [
            [
                'cache-method-names' => [self::CACHED_METHOD],
                'method-map-method-names' => [self::NOT_CACHED_METHOD],
                'expected-method-names' => [self::CACHED_METHOD],
            ],
            [
                'cache-method-names' => [self::CACHED_METHOD],
                'method-map-method-names' => [self::CACHED_METHOD],
                'expected-method-names' => [self::CACHED_METHOD],
            ],
            [
                'cache-method-names' => [],
                'method-map-method-names' => [self::NOT_CACHED_METHOD],
                'expected-method-names' => [],
            ],
            [
                'cache-method-names' => null,
                'method-map-method-names' => [self::NOT_CACHED_METHOD],
                'expected-method-names' => [self::NOT_CACHED_METHOD],
            ],
        ];
    }
}
