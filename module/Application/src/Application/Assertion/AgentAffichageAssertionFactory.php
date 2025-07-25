<?php

namespace Application\Assertion;

use Agent\Service\AgentAffectation\AgentAffectationService;
use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Interop\Container\ContainerInterface;
use Laminas\Mvc\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Observateur\ObservateurService;
use Structure\Service\Structure\StructureService;
use UnicaenPrivilege\Service\Privilege\PrivilegeCategorieService;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenUtilisateur\Service\User\UserService;

class AgentAffichageAssertionFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentAffichageAssertion
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var AgentAffectationService $agentAffectationService
         * @var ObservateurService $observateurService
         * @var StructureService $structureService
         * @var UserService $userService
         *
         * @var PrivilegeService $privilegeService
         * @var PrivilegeCategorieService $categorieService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $observateurService = $container->get(ObservateurService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        $privilegeService = $container->get(PrivilegeService::class);
        $categorieService = $container->get(PrivilegeCategorieService::class);

        $assertion = new AgentAffichageAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setAgentAutoriteService($agentAutoriteService);
        $assertion->setAgentSuperieurService($agentSuperieurService);
        $assertion->setAgentAffectationService($agentAffectationService);
        $assertion->setObservateurService($observateurService);
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);

        $assertion->setPrivilegeService($privilegeService);
        $assertion->setPrivilegeCategorieService($categorieService);

        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);
        return $assertion;
    }
}