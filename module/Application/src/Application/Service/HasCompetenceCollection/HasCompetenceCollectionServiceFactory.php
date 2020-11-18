<?php

namespace Application\Service\HasCompetenceCollection;

use Application\Service\Competence\CompetenceService;
use Application\Service\CompetenceElement\CompetenceElementService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class HasCompetenceCollectionServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return HasCompetenceCollectionService
     */
    public function __invoke(ContainerInterface $container)
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
        $service->setEntityManager($entityManager);
        $service->setCompetenceService($competenceService);
        $service->setCompetenceElementService($competenceElementService);
        $service->setUserService($userService);
        return $service;
    }
}