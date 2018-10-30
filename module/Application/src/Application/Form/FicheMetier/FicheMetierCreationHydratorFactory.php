<?php

namespace Application\Form\FicheMetier;

use Application\Service\Affectation\AffectationService;
use Zend\ServiceManager\ServiceLocatorInterface;

class FicheMetierCreationHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /** @var AffectationService $affectationService */
        $affectationService = $parentLocator->get(AffectationService::class);

        $hydrator = new FicheMetierCreationHydrator();
        $hydrator->setAffectationService($affectationService);

        return $hydrator;
    }
}