<?php

namespace Autoform\Service\Formulaire;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class FormulaireInstanceServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var FormulaireService $formulaireService
         * @var FormulaireReponseService $formulaireReponseService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $formulaireService = $container->get(FormulaireService::class);
        $formulaireReponseService = $container->get(FormulaireReponseService::class);

        /** @var FormulaireInstanceService $service */
        $service = new FormulaireInstanceService();
        $service->setEntityManager($entityManager);
        $service->setFormulaireService($formulaireService);
        $service->setFormulaireReponseService($formulaireReponseService);
        return $service;
    }
}