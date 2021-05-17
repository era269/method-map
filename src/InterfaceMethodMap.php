<?php

declare(strict_types=1);

namespace Era269\MethodMap;

use ReflectionClass;

final class InterfaceMethodMap extends AbstractMethodMap implements MethodMapInterface
{
    /**
     * @inheritDoc
     */
    public function getMethodNames($object): array
    {
        $methods = [];
        foreach ($this->getMap() as $interface => $methodNames) {
            if ($object instanceof $interface) {
                $methods = array_merge($methods, $methodNames);
            }
        }
        return $methods;
    }

    protected function isParameterValid(ReflectionClass $parameter): bool
    {
        return $parameter->isInterface()
            || $parameter->isAbstract();
    }
}
