<?php

namespace Formation\Controller;

use Application\Form\ApplicationElement\ApplicationElementForm;
use Application\Form\CompetenceElement\CompetenceElementForm;
use Application\Service\ApplicationElement\ApplicationElementService;
use Application\Service\CompetenceElement\CompetenceElementService;
use Formation\Form\Formation\FormationForm;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\FormationInstance\FormationInstanceService;
use Interop\Container\ContainerInterface;

class FormationControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationController
     */
    public function __invoke(ContainerInterface $container) : FormationController
    {
        /**
         * @var FormationService $formationService
         * @var FormationGroupeService $formationGroupeService
         * @var FormationInstanceService $formationInstanceService
         */
        $formationService = $container->get(FormationService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);

        /**
         * @var FormationForm $formationForm
         */
        $formationForm = $container->get('FormElementManager')->get(FormationForm::class);

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

        /** @var FormationController $controller */
        $controller = new FormationController();
        $controller->setFormationService($formationService);
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationForm($formationForm);

        $controller->setApplicationElementService($applicationElementService);
        $controller->setApplicationElementForm($applicationElementForm);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setCompetenceElementForm($competenceElementForm);

        return $controller;
    }

}