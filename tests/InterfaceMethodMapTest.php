<?php

declare(strict_types=1);

namespace Era269\MethodMap\Tests;

use DateTime;
use DateTimeInterface;
use Era269\MethodMap\InterfaceMethodMap;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;

class InterfaceMethodMapTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param $object
     * @param string[] $expectedMethods
     *
     * @throws ReflectionException
     */
    public function testGetMethodNames($object, int $visibility, array $expectedMethods): void
    {
        $methodMap = new InterfaceMethodMap(get_class($object), $visibility);
        $actualMethods = $methodMap->getMethodNames(new DateTime());

        self::assertEquals(
            $expectedMethods,
            $actualMethods
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

            private function doActionDateTimeInterface1Private(DateTimeInterface $dateTime)
            {
                return $dateTime->getTimestamp();
            }

            private function doActionDateTimeInterface2Private(DateTimeInterface $dateTime)
            {
                return $dateTime->getTimestamp();
            }

            public function doActionDateTimeInterfacePublic(DateTimeInterface $dateTime)
            {
                return $dateTime->getTimestamp();
            }
        };

        return [
            [
                $object,
                ReflectionMethod::IS_PUBLIC,
                'expected-methods' => [
                    'doActionDateTimeInterfacePublic',
                ],
            ],
            [
                $object,
                ReflectionMethod::IS_PROTECTED,
                'expected-methods' => [
                ],
            ],
            [
                $object,
                ReflectionMethod::IS_PRIVATE,
                'expected-methods' => [
                    'doActionDateTimeInterface1Private',
                    'doActionDateTimeInterface2Private',
                ],
            ],
        ];
    }
}
