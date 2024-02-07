<?php

namespace Formation\Controller;

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
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\PlanDeFormation\PlanDeFormationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
         * @var FormationService $formationService
         * @var FormationElementService $formationElementService
         * @var FormationGroupeService $formationGroupeService
         * @var FormationInstanceService $formationInstanceService
         * @var PlanDeFormationService $planDeFormationService
         */
        $actionCoutPrevisionnelService = $container->get(ActionCoutPrevisionnelService::class);
        $formationService = $container->get(FormationService::class);
        $formationElementService = $container->get(FormationElementService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $planDeFormationService = $container->get(PlanDeFormationService::class);

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
        $controller->setFormationService($formationService);
        $controller->setFormationElementService($formationElementService);
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationForm($formationForm);
        $controller->setPlanDeFormationService($planDeFormationService);
        $controller->setSelectionFormationForm($selectionFormationForm);

        $controller->setApplicationElementService($applicationElementService);
        $controller->setApplicationElementForm($applicationElementForm);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setCompetenceElementForm($competenceElementForm);

        return $controller;
    }

}