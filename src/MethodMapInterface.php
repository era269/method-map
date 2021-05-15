<?php

declare(strict_types=1);

namespace Era269\MethodMap;

interface MethodMapInterface
{
    /**
     * @param object $object
     *
     * @return string[]
     */
    public function getMethodNames($object): array;
}
