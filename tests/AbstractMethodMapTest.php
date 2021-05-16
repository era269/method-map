<?php

declare(strict_types=1);

namespace Era269\MethodMap\Tests;

use DateTime;
use Era269\MethodMap\AbstractMethodMap;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;

class AbstractMethodMapTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param $object
     * @param string[] $expectedMethods
     *
     * @throws ReflectionException
     */
    public function testGetMethodNames($object, bool $isParameterValid, int $visibility, array $expectedMethods): void
    {
        $methodMap = $this->createMethodMapMock(get_class($object), $visibility);
        $methodMap
            ->method('isParameterValid')
            ->willReturn($isParameterValid);
        self::assertEquals(
            $expectedMethods,
            $methodMap->getMethodNames(new DateTime())
        );
    }

    /**
     * @return array[]
     */
    public function dataProvider(): array
    {
        $object = new class {
            private function doActionDateTimePrivate(DateTime $dateTime)
            {
                return $dateTime->getTimestamp();
            }
            private function doActionDateTime2Private(DateTime $dateTime)
            {
                return $dateTime->getTimestamp();
            }
            protected function doActionDateTimeImmutableProtected(DateTime $dateTime)
            {
                return $dateTime->getTimestamp();
            }
            public function doActionDateTimePublic(DateTime $dateTime)
            {
                return $dateTime->getTimestamp();
            }
            public function doActionDateTimeInterfacePublic(DateTime $dateTime)
            {
                return $dateTime->getTimestamp();
            }
        };

        return [
            [
                $object,
                'is-parameterValid' => true,
                ReflectionMethod::IS_PUBLIC,
                'expected-methods' => [
                    'doActionDateTimePublic',
                ],
            ],
            [
                $object,
                'is-parameterValid' => true,
                ReflectionMethod::IS_PROTECTED,
                'expected-methods' => [
                    'doActionDateTimeImmutableProtected',
                ],
            ],
            [
                $object,
                'is-parameterValid' => true,
                ReflectionMethod::IS_PRIVATE,
                'expected-methods' => [
                    'doActionDateTimePrivate',
                    'doActionDateTime2Private',
                ],
            ],
            [
                $object,
                'is-parameterValid' => false,
                ReflectionMethod::IS_PUBLIC,
                'expected-methods' => [],
            ],
            [
                $object,
                'is-parameterValid' => false,
                ReflectionMethod::IS_PROTECTED,
                'expected-methods' => [],
            ],
            [
                $object,
                'is-parameterValid' => false,
                ReflectionMethod::IS_PRIVATE,
                'expected-methods' => [],
            ],
        ];
    }

    /**
     * @return AbstractMethodMap|MockObject
     * @throws ReflectionException
     */
    private function createMethodMapMock(string $className, int $visibility): AbstractMethodMap
    {
        return $this->getMockForAbstractClass(
            AbstractMethodMap::class,
            [$className, $visibility]
        );
    }
}
