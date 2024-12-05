<?php

namespace Structure\Assertion;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use Laminas\Mvc\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Observateur\ObservateurService;
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
         * @var ObservateurService $observateurService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $observateurService = $container->get(ObservateurService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        $assertion = new StructureAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setObservateurService($observateurService);
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);

        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent    = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);

        return $assertion;
    }

}