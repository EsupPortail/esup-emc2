<?php

namespace Agent\Assertion;


use Agent\Service\Agent\AgentService;
use Agent\Service\AgentAutorite\AgentAutoriteService;
use Agent\Service\AgentMobilite\AgentMobiliteService;
use Agent\Service\AgentSuperieur\AgentSuperieurService;
use Application\Service\Util\UtilService;
use Laminas\Mvc\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Observateur\ObservateurService;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\User\UserService;

class AgentMobiliteAssertionFactory
{

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentMobiliteAssertion
    {
        /**
         * @var AgentService $agentService
         * @var AgentMobiliteService $agentMobiliteService
         * @var UserService $userService
         * @var UtilService $utilService
         */
        $agentService = $container->get(AgentService::class);
        $agentMobiliteService = $container->get(AgentMobiliteService::class);
        $userService = $container->get(UserService::class);
        $utilService = $container->get(UtilService::class);

        $assertion = new AgentMobiliteAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setAgentMobiliteService($agentMobiliteService);
        $assertion->setUserService($userService);
        $assertion->setUtilService($utilService);


        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);

        return $assertion;
    }
}
