<?php

namespace Formation\Controller;

use Application\Form\ApplicationElement\ApplicationElementForm;
use Application\Form\CompetenceElement\CompetenceElementForm;
use Application\Service\ApplicationElement\ApplicationElementService;
use Application\Service\CompetenceElement\CompetenceElementService;
use Formation\Form\Formation\FormationForm;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\FormationTheme\FormationThemeService;
use Interop\Container\ContainerInterface;
use UnicaenEtat\Service\Etat\EtatService;

class FormationControllerFactory
{

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EtatService $etatService
         * @var FormationService $formationService
         * @var FormationGroupeService $formationGroupeService
         * @var FormationThemeService $formationThemeService
         */
        $etatService = $container->get(EtatService::class);
        $formationService = $container->get(FormationService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);
        $formationThemeService = $container->get(FormationThemeService::class);

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
        $controller->setEtatService($etatService);
        $controller->setFormationService($formationService);
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setFormationThemeService($formationThemeService);
        $controller->setFormationForm($formationForm);

        $controller->setApplicationElementService($applicationElementService);
        $controller->setApplicationElementForm($applicationElementForm);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setCompetenceElementForm($competenceElementForm);

        return $controller;
    }

}