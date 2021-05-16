<?php

declare(strict_types=1);

namespace Era269\MethodMap;

final class MethodMapCollectionDecorator implements MethodMapInterface
{
    /**
     * @var MethodMapInterface[]
     */
    private $methodRouters;

    public function __construct(MethodMapInterface ...$methodRouters)
    {
        $this->methodRouters = $methodRouters;
    }

    /**
     * @inheritDoc
     */
    public function getMethodNames($object): array
    {
        $methods = [];
        foreach ($this->methodRouters as $methodRouter) {
            $methods = array_merge(
                $methodRouter->getMethodNames($object),
                $methods
            );
        }

        return $methods;
    }
}
