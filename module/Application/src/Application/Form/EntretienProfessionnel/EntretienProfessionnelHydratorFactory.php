<?php

namespace Application\Form\EntretienProfessionnel;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class EntretienProfessionnelHydratorFactory {

    public function __invoke(ContainerInterface $manager)
    {
        /**
         * @var AgentService $agentService
         * @var UserService $userService
         */
        $agentService = $manager->get(AgentService::class);
        $userService = $manager->get(UserService::class);

        /**
         * @var EntretienProfessionnelHydrator $hydrator
         */
        $hydrator = new EntretienProfessionnelHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setUserService($userService);

        return $hydrator;
    }
}