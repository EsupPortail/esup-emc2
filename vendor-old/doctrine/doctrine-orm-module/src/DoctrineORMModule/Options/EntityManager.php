<?php

declare(strict_types=1);

namespace DoctrineORMModule\Options;

use Laminas\Stdlib\AbstractOptions;

class EntityManager extends AbstractOptions
{
    /**
     * Set the configuration key for the Configuration. Configuration key
     * is assembled as "doctrine.configuration.{key}" and pulled from
     * service locator.
     *
     * @var string
     */
    protected $configuration = 'orm_default';

    /**
     * Set the connection key for the Connection. Connection key
     * is assembled as "doctrine.connection.{key}" and pulled from
     * service locator.
     *
     * @var string
     */
    protected $connection = 'orm_default';

    /**
     * Set the connection key for the EntityResolver, which is
     * a service of type {@see \Doctrine\ORM\Tools\ResolveTargetEntityListener}.
     * The EntityResolver service name is assembled
     * as "doctrine.entity_resolver.{key}"
     *
     * @var string
     */
    protected $entityResolver = 'orm_default';

    public function setConfiguration(string $configuration): self
    {
        $this->configuration = $configuration;

        return $this;
    }

    public function getConfiguration(): string
    {
        return 'doctrine.configuration.' . $this->configuration;
    }

    public function setConnection(string $connection): self
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @return self
     */
    public function getConnection(): string
    {
        return 'doctrine.connection.' . $this->connection;
    }

    public function setEntityResolver(string $entityResolver): self
    {
        $this->entityResolver = (string) $entityResolver;

        return $this;
    }

    /**
     * @return self
     */
    public function getEntityResolver(): string
    {
        return 'doctrine.entity_resolver.' . $this->entityResolver;
    }
}
