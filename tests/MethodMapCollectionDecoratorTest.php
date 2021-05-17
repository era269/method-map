<?php

declare(strict_types=1);

namespace Era269\MethodMap\Tests;

use DateTime;
use Era269\MethodMap\MethodMapCollectionDecorator;
use Era269\MethodMap\MethodMapInterface;
use PHPUnit\Framework\TestCase;

class MethodMapCollectionDecoratorTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param array[] $actualMethodNamesResults
     * @param string[] $expectedMethodNames
     */
    public function testGetMethodNames(array $actualMethodNamesResults, array $expectedMethodNames): void
    {
        $methodMaps = [];
        foreach ($actualMethodNamesResults as $actualMethodNames) {
            $methodMap = $this->createMock(MethodMapInterface::class);
            $methodMap
                ->method('getMethodNames')
                ->willReturn($actualMethodNames);
            $methodMaps[] = $methodMap;
        }
        $methodMapCollection = new MethodMapCollectionDecorator(...$methodMaps);
        self::assertEquals(
            $expectedMethodNames,
            $methodMapCollection->getMethodNames(new DateTime())
        );
    }

    /**
     * @return array<int, array<string, array<int, array<int, string>|string>>>
     */
    public function dataProvider(): array
    {
        return [
            [
                'actual-method-names' => [
                    ['method11'],
                    [],
                    ['method31', 'method32']
                ],
                'expected' => ['method11', 'method31', 'method32']
            ],
            [
                'actual-method-names' => [
                    [],
                    [],
                    []
                ],
                'expected' => []
            ],
            [
                'actual-method-names' => [
                    ['method11'],
                    ['method21'],
                    ['method31']
                ],
                'expected' => ['method11', 'method21', 'method31']
            ],
            [
                'actual-method-names' => [
                    [],
                    [],
                    ['method31', 'method32']],
                'expected' => ['method31', 'method32']
            ],
        ];
    }
}
