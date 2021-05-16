<?php

declare(strict_types=1);

namespace Era269\MethodMap\Tests;

use DateTime;
use DateTimeInterface;
use Era269\MethodMap\ClassNameMethodMap;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;

class ClassNameMethodMapTest extends TestCase
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
        $methodMap = new ClassNameMethodMap(get_class($object), $visibility);
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
                    'doActionDateTimePublic',
                ],
            ],
            [
                $object,
                ReflectionMethod::IS_PROTECTED,
                'expected-methods' => [
                    'doActionDateTimeImmutableProtected',
                ],
            ],
            [
                $object,
                ReflectionMethod::IS_PRIVATE,
                'expected-methods' => [
                    'doActionDateTimePrivate',
                    'doActionDateTime2Private',
                ],
            ],
        ];
    }
}
