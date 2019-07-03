<?php

namespace Application\Form\RessourceRh;

use Application\Service\Domaine\DomaineService;
use Zend\ServiceManager\ServiceLocatorInterface;

class MetierHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /** @var DomaineService $domaineService */
        $domaineService = $parentLocator->get(DomaineService::class);

        $hydrator = new MetierHydrator();
        $hydrator->setDomaineService($domaineService);

        return $hydrator;
    }
}