<?php

namespace Application\Form\RessourceRh;

use Application\Service\Domaine\DomaineService;
use Interop\Container\ContainerInterface;

class FonctionHydratorFactory {

    public function __invoke(ContainerInterface $manager)
    {
        /**
         * @var DomaineService $domaineService
         */
        $domaineService = $manager->get(DomaineService::class);

        /** @var FonctionHydrator $hydrator */
        $hydrator = new FonctionHydrator();
        $hydrator->setDomaineService($domaineService);
        return $hydrator;
    }
}