<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\AgentAffectation\AgentAffectationService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentGrade\AgentGradeService;
use Application\Service\AgentStatut\AgentStatutService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Formation\Service\DemandeExterne\DemandeExterneService;
use Formation\Service\Inscription\InscriptionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenUtilisateur\Service\User\UserService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;

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
         * @var InscriptionService $inscriptionService
         * @var RenduService $renduService
         * @var UserService $userService
         * @var ValidationInstanceService $validationInstanceService
         */
        $agentService = $container->get(AgentService::class);
        $affectationService = $container->get(AgentAffectationService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $gradeService = $container->get(AgentGradeService::class);
        $statutService = $container->get(AgentStatutService::class);
        $demandeExterneService = $container->get(DemandeExterneService::class);
        $inscriptionService = $container->get(InscriptionService::class);
        $renduService = $container->get(RenduService::class);
        $userService = $container->get(UserService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);

        $controller = new AgentController();
        $controller->setAgentService($agentService);
        $controller->setAgentAffectationService($affectationService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setAgentGradeService($gradeService);
        $controller->setAgentStatutService($statutService);
        $controller->setDemandeExterneService($demandeExterneService);
        $controller->setInscriptionService($inscriptionService);
        $controller->setRenduService($renduService);
        $controller->setUserService($userService);
        $controller->setValidationInstanceService($validationInstanceService);

        return $controller;
    }
}