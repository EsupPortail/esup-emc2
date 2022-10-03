<?php

namespace Structure\Assertion;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use Laminas\Mvc\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\User\UserService;

class StructureAssertionFactory
{
    /**
     * @param ContainerInterface $container
     * @return StructureAssertion
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : StructureAssertion
    {
        /**
         * @var AgentService $agentService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var StructureAssertion $assertion */
        $assertion = new StructureAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);

        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent    = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);

        return $assertion;
    }

}