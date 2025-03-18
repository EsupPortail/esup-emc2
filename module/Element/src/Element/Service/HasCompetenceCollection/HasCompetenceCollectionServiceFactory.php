<?php

namespace Element\Service\HasCompetenceCollection;

use Doctrine\ORM\EntityManager;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Service\User\UserService;

class HasCompetenceCollectionServiceFactory {

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : HasCompetenceCollectionService
    {
        /**
         * @var EntityManager $entityManager
         * @var CompetenceService $competenceService
         * @var CompetenceElementService $competenceElementService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $competenceService = $container->get(CompetenceService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $userService = $container->get(UserService::class);

        $service = new HasCompetenceCollectionService();
        $service->setObjectManager($entityManager);
        $service->setCompetenceService($competenceService);
        $service->setCompetenceElementService($competenceElementService);
        $service->setUserService($userService);
        return $service;
    }
}