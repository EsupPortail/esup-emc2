<?php

namespace Structure\Assertion;

use Agent\Service\Agent\AgentService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Observateur\ObservateurService;
use Structure\Service\Structure\StructureService;
use Structure\Service\StructureGestionnaire\StructureGestionnaireService;
use Structure\Service\StructureResponsable\StructureResponsableService;
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
         * @var StructureService $structureService
         * @var StructureGestionnaireService $structureGestionnaireService
         * @var StructureResponsableService $structureResponsableService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $observateurService = $container->get(ObservateurService::class);
        $structureService = $container->get(StructureService::class);
        $structureGestionnaireService = $container->get(StructureGestionnaireService::class);
        $structureResponsableService = $container->get(StructureResponsableService::class);
        $userService = $container->get(UserService::class);

        $assertion = new ResponsabiliteAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setObservateurStructureService($observateurService);
        $assertion->setStructureService($structureService);
        $assertion->setStructureGestionnaireService($structureGestionnaireService);
        $assertion->setStructureResponsableService($structureResponsableService);
        $assertion->setUserService($userService);
        return $assertion;
    }
}
