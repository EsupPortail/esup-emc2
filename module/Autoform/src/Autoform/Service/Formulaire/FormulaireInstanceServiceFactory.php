<?php

namespace Autoform\Service\Formulaire;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class FormulaireInstanceServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var FormulaireService $formulaireService
         * @var FormulaireReponseService $formulaireReponseService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $formulaireService = $container->get(FormulaireService::class);
        $formulaireReponseService = $container->get(FormulaireReponseService::class);
        $userService = $container->get(UserService::class);

        /** @var FormulaireInstanceService $service */
        $service = new FormulaireInstanceService();
        $service->setEntityManager($entityManager);
        $service->setFormulaireService($formulaireService);
        $service->setFormulaireReponseService($formulaireReponseService);
        $service->setUserService($userService);
        return $service;
    }
}