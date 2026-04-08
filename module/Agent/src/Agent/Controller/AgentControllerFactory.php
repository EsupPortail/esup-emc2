<?php

namespace Agent\Controller;

use Agent\Service\AgentAffectation\AgentAffectationService;
use Agent\Service\AgentGrade\AgentGradeService;
use Agent\Service\AgentStatut\AgentStatutService;
use Application\Assertion\ChaineAssertion;
use Agent\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
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
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentAffectationService $agentAffectationService
         * @var AgentGradeService $agentGradeService
         * @var AgentMissionSpecifiqueService $agentMissionSpecifiqueService
         * @var AgentSuperieurService $agentSuperieurService
         * @var CampagneService $campagneService
         * @var ParametreService $parametreService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $agentGradeService = $container->get(AgentGradeService::class);
        $agentMissionSpecifiqueService = $container->get(AgentMissionSpecifiqueService::class);
        $agentStatutService = $container->get(AgentStatutService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $campagneService = $container->get(CampagneService::class);
        $parametreService = $container->get(ParametreService::class);
        $userService = $container->get(UserService::class);

        $controller = new AgentController();
        $controller->setAgentService($agentService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentAffectationService($agentAffectationService);
        $controller->setAgentGradeService($agentGradeService);
        $controller->setAgentMissionSpecifiqueService($agentMissionSpecifiqueService);
        $controller->setAgentStatutService($agentStatutService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setCampagneService($campagneService);
        $controller->setParametreService($parametreService);
        $controller->setUserService($userService);

        $assertion = $container->get(ChaineAssertion::class);
        $controller->chaineAssertion = $assertion;
        return $controller;
    }
}
