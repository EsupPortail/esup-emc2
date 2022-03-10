<?php

namespace Autoform\Service\Formulaire;

use Autoform\Service\Champ\ChampService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class FormulaireReponseServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var ChampService $champService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $champService = $container->get(ChampService::class);

        /** @var FormulaireReponseService $service */
        $service = new FormulaireReponseService();
        $service->setEntityManager($entityManager);
        $service->setChampService($champService);
        return $service;
    }
}