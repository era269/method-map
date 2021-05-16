<?php

declare(strict_types=1);

namespace Era269\MethodMap;

use ReflectionClass;
use ReflectionMethod;

abstract class AbstractMethodMap implements MethodMapInterface
{
    /**
     * @var class-string
     */
    private $className;
    /**
     * @var int
     */
    private $methodVisibility;

    /**
     * @param class-string $className
     */
    public function __construct(
        string $className,
        int $methodVisibility = ReflectionMethod::IS_PUBLIC
    ) {
        $this->className = $className;
        $this->methodVisibility = $methodVisibility;
    }

    /**
     * @inheritDoc
     */
    public function getMethodNames($object): array
    {
        return $this->getMap()[get_class($object)]
            ?? [];
    }

    /**
     * @return array<string, mixed>
     */
    final protected function getMap(): array
    {
        $methodNamesMap = [];

        $selfReflection = new ReflectionClass($this->className);
        foreach ($selfReflection->getMethods($this->methodVisibility) as $method) {
            if ($method->getNumberOfParameters() !== 1) {
                continue;
            }
            $parameterType = $method->getParameters()[0]->getType();
            if (is_null($parameterType)) {
                continue;
            }
            $parameterReflection = new ReflectionClass($parameterType);

            if (!$this->isParameterValid($parameterReflection)) {
                continue;
            }
            $methodNamesMap[$parameterReflection->getName()][] = $method->getName();
        }

        return $methodNamesMap;
    }

    /**
     * @param ReflectionClass<object> $parameter
     */
    abstract protected function isParameterValid(ReflectionClass $parameter): bool;
}
