<?php

namespace Application\Service\Activite;

use Application\Service\Application\ApplicationService;
use Application\Service\Competence\CompetenceService;
use Application\Service\Formation\FormationService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class ActiviteServiceFactory {
    /**
     * @param ContainerInterface $container
     * @return ActiviteService
     */
    public function __invoke(ContainerInterface $container) {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         * @var ApplicationService $applicationService
         * @var CompetenceService $competenceService
         * @var FormationService $formationService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);
        $applicationService = $container->get(ApplicationService::class);
        $competenceService = $container->get(CompetenceService::class);
        $formationService = $container->get(FormationService::class);

        /** @var ActiviteService $service */
        $service = new ActiviteService();
        $service->setEntityManager($entityManager);
        $service->setApplicationService($applicationService);
        $service->setCompetenceService($competenceService);
        $service->setFormationService($formationService);
        $service->setUserService($userService);

        return $service;
    }
}