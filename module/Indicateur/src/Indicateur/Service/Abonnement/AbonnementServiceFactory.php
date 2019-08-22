<?php

namespace Indicateur\Service\Abonnement;

use Zend\ServiceManager\ServiceLocatorInterface;

class AbonnementServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var AbonnementService $service */
        $service = new AbonnementService();
        return $service;
    }
}