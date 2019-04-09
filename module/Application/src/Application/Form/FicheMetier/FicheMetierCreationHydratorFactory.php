<?php

namespace Application\Form\FicheMetier;

use Zend\ServiceManager\ServiceLocatorInterface;

class FicheMetierCreationHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $hydrator = new FicheMetierCreationHydrator();

        return $hydrator;
    }
}