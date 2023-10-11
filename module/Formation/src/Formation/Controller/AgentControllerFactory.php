<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\AgentAffectation\AgentAffectationService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentGrade\AgentGradeService;
use Application\Service\AgentStatut\AgentStatutService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Formation\Service\DemandeExterne\DemandeExterneService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Service\User\UserService;

class AgentControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAffectationService $affectationService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentGradeService $gradeService
         * @var AgentStatutService $statutService
         * @var AgentSuperieurService $agentSuperieurService
         * @var DemandeExterneService $demandeExterneService
         * @var FormationInstanceInscritService $inscriptionService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $affectationService = $container->get(AgentAffectationService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $gradeService = $container->get(AgentGradeService::class);
        $statutService = $container->get(AgentStatutService::class);
        $demandeExterneService = $container->get(DemandeExterneService::class);
        $inscriptionService = $container->get(FormationInstanceInscritService::class);
        $userService = $container->get(UserService::class);

        $controller = new AgentController();
        $controller->setAgentService($agentService);
        $controller->setAgentAffectationService($affectationService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setAgentGradeService($gradeService);
        $controller->setAgentStatutService($statutService);
        $controller->setDemandeExterneService($demandeExterneService);
        $controller->setFormationInstanceInscritService($inscriptionService);
        $controller->setUserService($userService);

        return $controller;
    }
}