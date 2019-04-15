<?php

namespace Application\Form\FichePosteCreation;

use Zend\ServiceManager\ServiceLocatorInterface;

class FichePosteCreationHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $hydrator = new FichePosteCreationHydrator();

        return $hydrator;
    }
}