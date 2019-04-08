<?php

namespace Autoform\Service\Validation;

use Autoform\Service\Formulaire\FormulaireReponseService;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class ValidationReponseServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var FormulaireReponseService $formulaireReponseService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $formulaireReponseService   = $serviceLocator->get(FormulaireReponseService::class);

        /** @var ValidationReponseService $service */
        $service = new ValidationReponseService();
        $service->setEntityManager($entityManager);
        $service->setFormulaireReponseService($formulaireReponseService);
        return $service;
    }
}