<?php

namespace Application\Form\FicheMetier;


use Application\Service\FicheMetier\FicheMetierService;
use Zend\ServiceManager\ServiceLocatorInterface;

class AssocierMetierTypeHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /** @var FicheMetierService $ficheMetierService */
        $ficheMetierService = $parentLocator->get(FicheMetierService::class);

        $hydrator = new AssocierMetierTypeHydrator();
        $hydrator->setFicheMetierService($ficheMetierService);

        return $hydrator;
    }

}