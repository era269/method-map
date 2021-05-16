<?php

declare(strict_types=1);

namespace Era269\MethodMap;

use ReflectionClass;

final class ClassNameMethodMap extends AbstractMethodMap implements MethodMapInterface
{
    protected function isParameterValid(ReflectionClass $parameter): bool
    {
        return !$parameter->isInterface()
            && !$parameter->isAbstract();
    }
}
