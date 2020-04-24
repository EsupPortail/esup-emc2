<?php

namespace Application\Service\FicheMetier;

use Application\Service\Application\ApplicationService;
use Application\Service\Competence\CompetenceService;
use Application\Service\Formation\FormationService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FicheMetierServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheMetierService
     */
    public function __invoke(ContainerInterface $container)
    {
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

        /** @var FicheMetierService $service */
        $service = new FicheMetierService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        $service->setApplicationService($applicationService);
        $service->setCompetenceService($competenceService);
        $service->setFormationService($formationService);

        return $service;
    }
}