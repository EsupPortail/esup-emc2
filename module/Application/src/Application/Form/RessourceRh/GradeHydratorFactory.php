<?php

namespace Application\Form\RessourceRh;

use Application\Service\RessourceRh\RessourceRhService;
use Zend\ServiceManager\ServiceLocatorInterface;

class GradeHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /** @var RessourceRhService $ressourceService */
        $ressourceService = $parentLocator->get(RessourceRhService::class);

        $hydrator = new GradeHydrator();
        $hydrator->setRessourceRhService($ressourceService);

        return $hydrator;
    }
}