<?php

namespace Application\Form\RessourceRh;

use Application\Service\RessourceRh\RessourceRhService;
use Zend\ServiceManager\ServiceLocatorInterface;

class MetierHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /** @var RessourceRhService $ressourceService */
        $ressourceService = $parentLocator->get(RessourceRhService::class);

        $hydrator = new MetierHydrator();
        $hydrator->setRessourceRhService($ressourceService);

        return $hydrator;
    }
}