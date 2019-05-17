<?php

namespace Application\Form\RessourceRh;

use Application\Service\Fonction\FonctionService;
use Zend\ServiceManager\ServiceLocatorInterface;

class MetierHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /** @var FonctionService $fonctionService */
        $fonctionService = $parentLocator->get(FonctionService::class);

        $hydrator = new MetierHydrator();
        $hydrator->setFonctionService($fonctionService);

        return $hydrator;
    }
}