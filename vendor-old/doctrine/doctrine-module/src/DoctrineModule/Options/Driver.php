<?php

declare(strict_types=1);

namespace DoctrineModule\Options;

use Laminas\Stdlib\AbstractOptions;

/**
 * MappingDriver options
 *
 * @link    http://www.doctrine-project.org/
 */
class Driver extends AbstractOptions
{
    /**
     * The class name of the Driver.
     *
     * @var string
     */
    protected $class;

    /**
     * All drivers (except DriverChain) require paths to work on. You
     * may set this value as a string (for a single path) or an array
     * for multiple paths.
     *
     * @var mixed[]
     */
    protected $paths = [];

    /**
     * Set the cache key for the annotation cache. Cache key
     * is assembled as "doctrine.cache.{key}" and pulled from
     * service locator. This option is only valid for the
     * AnnotationDriver.
     *
     * @var string
     */
    protected $cache = 'array';

    /**
     * Set the file extension to use. This option is only
     * valid for FileDrivers (XmlDriver, YamlDriver, PHPDriver, etc).
     *
     * @var string|null
     */
    protected $extension = null;

    /**
     * Set the driver keys to use which are assembled as
     * "doctrine.driver.{key}" and pulled from the service
     * locator. This option is only valid for DriverChain.
     *
     * @var mixed[]
     */
    protected $drivers = [];

    public function setCache(string $cache): void
    {
        $this->cache = $cache;
    }

    public function getCache(): string
    {
        return 'doctrine.cache.' . $this->cache;
    }

    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param mixed[] $drivers
     */
    public function setDrivers(array $drivers): void
    {
        $this->drivers = $drivers;
    }

    /**
     * @return mixed[]
     */
    public function getDrivers(): array
    {
        return $this->drivers;
    }

    /**
     * @param null $extension
     */
    public function setExtension($extension): void
    {
        $this->extension = $extension;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * @param mixed[] $paths
     */
    public function setPaths(array $paths): void
    {
        $this->paths = $paths;
    }

    /**
     * @return mixed[]
     */
    public function getPaths(): array
    {
        return $this->paths;
    }
}
