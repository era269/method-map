<?php

declare(strict_types=1);

namespace Era269\MethodMap;

use Psr\SimpleCache\CacheInterface;

final class MethodMapCacheDecorator implements MethodMapInterface
{
    /**
     * @var MethodMapInterface
     */
    private $methodMap;
    /**
     * @var CacheInterface
     */
    private $cache;
    /**
     * @var string
     */
    private $keyPrefix;

    public function __construct(MethodMapInterface $methodMap, CacheInterface $cache, string $keyPrefix = '')
    {
        $this->methodMap = $methodMap;
        $this->cache = $cache;
        $this->keyPrefix = $keyPrefix;
    }

    /**
     * @inheritDoc
     */
    public function getMethodNames($object): array
    {
        $key = $this->getKey($object);
        $methodNames = $this->cache->get($key);
        if (is_null($methodNames)) {
            $methodNames = $this->methodMap->getMethodNames($object);
            $this->cache->set(
                $key,
                $methodNames
            );
        }

        return $methodNames;
    }

    /**
     * @param object $object
     *
     * @return string
     */
    private function getKey($object): string
    {
        return md5(sprintf(
            '%s-%s',
            $this->keyPrefix,
            get_class($object)
        ));
    }
}
