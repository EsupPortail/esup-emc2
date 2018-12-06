<?php

namespace Application\Form\FicheMetierType;

use Application\Service\RessourceRh\RessourceRhService;
use Zend\ServiceManager\ServiceLocatorInterface;

class LibelleHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /** @var RessourceRhService $ressourceService */
        $ressourceService = $parentLocator->get(RessourceRhService::class);

        $hydrator = new LibelleHydrator();
        $hydrator->setRessourceRhService($ressourceService);

        return $hydrator;
    }
}