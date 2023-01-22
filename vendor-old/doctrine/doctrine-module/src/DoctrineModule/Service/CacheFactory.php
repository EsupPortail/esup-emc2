<?php

declare(strict_types=1);

namespace DoctrineModule\Service;

use Doctrine\Common\Cache;
use Doctrine\Common\Cache\CacheProvider;
use DoctrineModule\Cache\LaminasStorageCache;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use RuntimeException;

use function is_string;

/**
 * Cache ServiceManager factory
 *
 * @link    http://www.doctrine-project.org/
 */
class CacheFactory extends AbstractFactory
{
    /**
     * {@inheritDoc}
     *
     * @return \Doctrine\Common\Cache\Cache
     *
     * @throws RuntimeException
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $options = $this->getOptions($container, 'cache');
        $class   = $options->getClass();

        if (! $class) {
            throw new RuntimeException('Cache must have a class name to instantiate');
        }

        $instance = $options->getInstance();

        if (is_string($instance) && $container->has($instance)) {
            $instance = $container->get($instance);
        }

        if ($container->has($class)) {
            $cache = $container->get($class);
        } else {
            switch ($class) {
                case Cache\FilesystemCache::class:
                    $cache = new $class($options->getDirectory());
                    break;

                case LaminasStorageCache::class:
                case Cache\PredisCache::class:
                    $cache = new $class($instance);
                    break;

                default:
                    $cache = new $class();
                    break;
            }
        }

        if ($cache instanceof Cache\MemcacheCache) {
            $cache->setMemcache($instance);
        } elseif ($cache instanceof Cache\MemcachedCache) {
            $cache->setMemcached($instance);
        } elseif ($cache instanceof Cache\RedisCache) {
            $cache->setRedis($instance);
        }

        if ($cache instanceof CacheProvider) {
            $namespace = $options->getNamespace();
            if ($namespace) {
                $cache->setNamespace($namespace);
            }
        }

        return $cache;
    }

    /**
     * {@inheritDoc}
     *
     * @return \Doctrine\Common\Cache\Cache
     *
     * @throws RuntimeException
     */
    public function createService(ServiceLocatorInterface $container)
    {
        return $this($container, \Doctrine\Common\Cache\Cache::class);
    }

    public function getOptionsClass(): string
    {
        return 'DoctrineModule\Options\Cache';
    }
}
