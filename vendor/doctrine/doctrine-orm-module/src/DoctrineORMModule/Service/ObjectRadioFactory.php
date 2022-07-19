<?php

declare(strict_types=1);

namespace DoctrineORMModule\Service;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Form\Element\ObjectRadio;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for {@see ObjectRadio}
 */
class ObjectRadioFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return ObjectRadio
     */
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, ?array $options = null)
    {
        $entityManager = $serviceLocator->get(EntityManager::class);
        $element       = new ObjectRadio();

        $element->getProxy()->setObjectManager($entityManager);

        return $element;
    }

    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator->getServiceLocator(), ObjectRadio::class);
    }
}
