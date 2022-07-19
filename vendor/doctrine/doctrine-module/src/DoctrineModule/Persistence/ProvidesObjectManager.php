<?php

declare(strict_types=1);

namespace DoctrineModule\Persistence;

use Doctrine\Persistence\ObjectManager;

use function interface_exists;

/**
 * Trait to provide object manager to a form (only works from PHP 5.4)
 */
trait ProvidesObjectManager
{
    /** @var ObjectManager */
    protected $objectManager;

    /**
     * Set the object manager
     */
    public function setObjectManager(ObjectManager $objectManager): void
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Get the object manager
     */
    public function getObjectManager(): ObjectManager
    {
        return $this->objectManager;
    }
}

interface_exists(ObjectManager::class);
