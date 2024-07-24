<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Element\Form\ApplicationElement\ApplicationElementForm;
use Element\Form\CompetenceElement\CompetenceElementForm;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Formation\Form\Formation\FormationForm;
use Formation\Form\SelectionFormation\SelectionFormationForm;
use Formation\Service\ActionCoutPrevisionnel\ActionCoutPrevisionnelService;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationElement\FormationElementService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\Inscription\InscriptionService;
use Formation\Service\PlanDeFormation\PlanDeFormationService;
use Formation\Service\Session\SessionService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEnquete\Service\Enquete\EnqueteService;
use UnicaenEnquete\Service\Resultat\ResultatService;
use UnicaenParametre\Service\Parametre\ParametreService;

class FormationControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationController
    {
        /**
         * @var ActionCoutPrevisionnelService $actionCoutPrevisionnelService
         * @var AgentService $agentService
         * @var EnqueteService $enqueteService
         * @var FormationService $formationService
         * @var FormationElementService $formationElementService
         * @var FormationGroupeService $formationGroupeService
         * @var InscriptionService $inscriptionService
         * @var ParametreService $parametreService
         * @var PlanDeFormationService $planDeFormationService
         * @var ResultatService $resultatService
         * @var SessionService $sessionService
         */
        $actionCoutPrevisionnelService = $container->get(ActionCoutPrevisionnelService::class);
        $agentService = $container->get(AgentService::class);
        $enqueteService = $container->get(EnqueteService::class);
        $formationService = $container->get(FormationService::class);
        $formationElementService = $container->get(FormationElementService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);
        $inscriptionService = $container->get(InscriptionService::class);
        $parametreService = $container->get(ParametreService::class);
        $planDeFormationService = $container->get(PlanDeFormationService::class);
        $resultatService = $container->get(ResultatService::class);
        $sessionService = $container->get(SessionService::class);

        /**
         * @var FormationForm $formationForm
         * @var SelectionFormationForm $selectionFormationForm
         */
        $formationForm = $container->get('FormElementManager')->get(FormationForm::class);
        $selectionFormationForm = $container->get('FormElementManager')->get(SelectionFormationForm::class);

        /**
         * @var ApplicationElementService $applicationElementService
         * @var ApplicationElementForm $applicationElementForm
         * @var CompetenceElementService $competenceElementService
         * @var CompetenceElementForm $competenceElementForm
         */
        $applicationElementService = $container->get(ApplicationElementService::class);
        $applicationElementForm = $container->get('FormElementManager')->get(ApplicationElementForm::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $competenceElementForm = $container->get('FormElementManager')->get(CompetenceElementForm::class);

        $controller = new FormationController();
        $controller->setActionCoutPrevisionnelService($actionCoutPrevisionnelService);
        $controller->setAgentService($agentService);
        $controller->setEnqueteService($enqueteService);
        $controller->setFormationService($formationService);
        $controller->setFormationElementService($formationElementService);
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setFormationForm($formationForm);
        $controller->setInscriptionService($inscriptionService);
        $controller->setParametreService($parametreService);
        $controller->setPlanDeFormationService($planDeFormationService);
        $controller->setResultatService($resultatService);
        $controller->setSelectionFormationForm($selectionFormationForm);
        $controller->setSessionService($sessionService);

        $controller->setApplicationElementService($applicationElementService);
        $controller->setApplicationElementForm($applicationElementForm);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setCompetenceElementForm($competenceElementForm);

        return $controller;
    }

}