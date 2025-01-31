<?php

namespace Application\Assertion;

use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use FichePoste\Service\FichePoste\FichePosteService;
use Interop\Container\ContainerInterface;
use Laminas\Mvc\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Observateur\ObservateurService;
use Structure\Service\Structure\StructureService;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenUtilisateur\Service\User\UserService;

class FichePosteAssertionFactory
{
    /**
     * @param ContainerInterface $container
     * @return FichePosteAssertion
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FichePosteAssertion
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var FichePosteService $fichePosteService
         * @var ObservateurService $observateurService
         * @var StructureService $structureService
         *
         * @var PrivilegeService $privilegeService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $observateurService = $container->get(ObservateurService::class);
        $structureService = $container->get(StructureService::class);

        $privilegeService = $container->get(PrivilegeService::class);
        $userService = $container->get(UserService::class);

        $assertion = new FichePosteAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setAgentAutoriteService($agentAutoriteService);
        $assertion->setAgentSuperieurService($agentSuperieurService);
        $assertion->setFichePosteService($fichePosteService);
        $assertion->setObservateurService($observateurService);
        $assertion->setStructureService($structureService);

        $assertion->setPrivilegeService($privilegeService);
        $assertion->setUserService($userService);

        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent    = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);

        return $assertion;
    }

}