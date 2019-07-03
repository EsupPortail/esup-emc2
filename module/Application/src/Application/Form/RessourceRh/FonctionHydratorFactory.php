<?php

namespace Application\Form\RessourceRh;

use Application\Service\Domaine\DomaineService;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

class FonctionHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /**
         * @var DomaineService $domaineService
         */
        $domaineService = $manager->getServiceLocator()->get(DomaineService::class);

        /** @var FonctionHydrator $hydrator */
        $hydrator = new FonctionHydrator();
        $hydrator->setDomaineService($domaineService);
        return $hydrator;
    }
}