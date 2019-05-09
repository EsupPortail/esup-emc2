<?php

namespace Application\Form\EntretienProfessionnel;

use Application\Service\Agent\AgentService;
use Utilisateur\Service\User\UserService;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

class EntretienProfessionnelHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /**
         * @var AgentService $agentService
         * @var UserService $userService
         */
        $agentService = $manager->getServiceLocator()->get(AgentService::class);
        $userService = $manager->getServiceLocator()->get(UserService::class);

        /**
         * @var EntretienProfessionnelHydrator $hydrator
         */
        $hydrator = new EntretienProfessionnelHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setUserService($userService);

        return $hydrator;
    }
}