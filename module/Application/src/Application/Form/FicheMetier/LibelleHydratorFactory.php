<?php

namespace Application\Form\FicheMetier;

use Application\Service\Metier\MetierService;
use Zend\ServiceManager\ServiceLocatorInterface;

class LibelleHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /** @var MetierService $metierService */
        $metierService = $parentLocator->get(MetierService::class);

        $hydrator = new LibelleHydrator();
        $hydrator->setMetierService($metierService);

        return $hydrator;
    }
}