<?php

namespace Application\Assertion;


use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Laminas\Mvc\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Observateur\ObservateurService;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\User\UserService;

class ChaineAssertionFactory {

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ChaineAssertion
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var StructureService $structureService
         * @var ObservateurService $observateurService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $structureService = $container->get(StructureService::class);
        $observateurService = $container->get(ObservateurService::class);
        $userService = $container->get(UserService::class);

        $assertion = new ChaineAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setAgentAutoriteService($agentAutoriteService);
        $assertion->setAgentSuperieurService($agentSuperieurService);
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