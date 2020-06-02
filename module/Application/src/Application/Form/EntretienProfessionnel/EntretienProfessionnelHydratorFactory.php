<?php

namespace Application\Form\EntretienProfessionnel;

use Application\Service\Agent\AgentService;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelCampagneService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class EntretienProfessionnelHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var EntretienProfessionnelCampagneService $campagneService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $campagneService = $container->get(EntretienProfessionnelCampagneService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var EntretienProfessionnelHydrator $hydrator
         */
        $hydrator = new EntretienProfessionnelHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setEntretienProfessionnelCampagneService($campagneService);
        $hydrator->setUserService($userService);

        return $hydrator;
    }
}
