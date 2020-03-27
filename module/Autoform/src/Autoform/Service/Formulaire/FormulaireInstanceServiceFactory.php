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
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);
        $formulaireReponseService = $container->get(FormulaireReponseService::class);

        /** @var FormulaireInstanceService $service */
        $service = new FormulaireInstanceService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        $service->setFormulaireReponseService($formulaireReponseService);
        return $service;
    }
}