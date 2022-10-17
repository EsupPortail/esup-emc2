<?php

namespace Formation\Assertion;

use Application\Service\Agent\AgentService;
use Formation\Service\DemandeExterne\DemandeExterneService;
use Laminas\Mvc\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Service\User\UserService;

class DemandeExterneAssertionFactory
{

    /**
     * @param ContainerInterface $container
     * @return DemandeExterneAssertion
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DemandeExterneAssertion
    {
        /**
         * @var AgentService $agentService
         * @var DemandeExterneService $demandeExterneService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $demandeExterneService = $container->get(DemandeExterneService::class);
        $userService = $container->get(UserService::class);

        $assertion = new DemandeExterneAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setDemandeExterneService($demandeExterneService);
        $assertion->setUserService($userService);

        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);
        return $assertion;
    }
}