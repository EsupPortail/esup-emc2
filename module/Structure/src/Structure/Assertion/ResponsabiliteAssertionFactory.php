<?php

namespace Structure\Assertion;

use Agent\Service\Agent\AgentService;
use Laminas\Mvc\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Observateur\ObservateurService;
use Structure\Service\Structure\StructureService;
use Structure\Service\StructureGestionnaire\StructureGestionnaireService;
use Structure\Service\StructureResponsable\StructureResponsableService;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenUtilisateur\Service\User\UserService;

class ResponsabiliteAssertionFactory
{

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ResponsabiliteAssertion
    {
        /**
         * @var AgentService $agentService
         * @var ObservateurService $observateurService
         * @var PrivilegeService $privilegeService
         * @var StructureService $structureService
         * @var StructureGestionnaireService $structureGestionnaireService
         * @var StructureResponsableService $structureResponsableService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $observateurService = $container->get(ObservateurService::class);
        $privilegeService = $container->get(PrivilegeService::class);
        $structureService = $container->get(StructureService::class);
        $structureGestionnaireService = $container->get(StructureGestionnaireService::class);
        $structureResponsableService = $container->get(StructureResponsableService::class);
        $userService = $container->get(UserService::class);

        $assertion = new ResponsabiliteAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setObservateurStructureService($observateurService);
        $assertion->setPrivilegeService($privilegeService);
        $assertion->setStructureService($structureService);
        $assertion->setStructureGestionnaireService($structureGestionnaireService);
        $assertion->setStructureResponsableService($structureResponsableService);
        $assertion->setUserService($userService);

        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent    = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);
        return $assertion;
    }
}
