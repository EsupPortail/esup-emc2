<?php

namespace EntretienProfessionnel\Form\EntretienProfessionnel;

use Application\Service\Agent\AgentService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Service\User\UserService;

class EntretienProfessionnelHydratorFactory {

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container)  :EntretienProfessionnelHydrator
    {
        /**
         * @var AgentService $agentService
         * @var CampagneService $campagneService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $campagneService = $container->get(CampagneService::class);
        $userService = $container->get(UserService::class);

        $hydrator = new EntretienProfessionnelHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setCampagneService($campagneService);
        $hydrator->setUserService($userService);

        return $hydrator;
    }
}
