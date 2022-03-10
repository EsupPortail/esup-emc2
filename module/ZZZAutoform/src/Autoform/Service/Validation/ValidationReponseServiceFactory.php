<?php

namespace Autoform\Service\Validation;

use Autoform\Service\Formulaire\FormulaireReponseService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ValidationReponseServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var FormulaireReponseService $formulaireReponseService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $formulaireReponseService   = $container->get(FormulaireReponseService::class);

        /** @var ValidationReponseService $service */
        $service = new ValidationReponseService();
        $service->setEntityManager($entityManager);
        $service->setFormulaireReponseService($formulaireReponseService);
        return $service;
    }
}