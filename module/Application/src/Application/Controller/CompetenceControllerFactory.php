<?php

namespace Application\Controller;

use Application\Form\Competence\CompetenceForm;
use Application\Form\CompetenceElement\CompetenceElementForm;
use Application\Form\CompetenceType\CompetenceTypeForm;
use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Form\SelectionCompetence\SelectionCompetenceForm;
use Application\Form\SelectionCompetenceMaitrise\SelectionCompetenceMaitriseForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\Agent\AgentService;
use Application\Service\Competence\CompetenceService;
use Application\Service\CompetenceElement\CompetenceElementService;
use Application\Service\CompetenceMaitrise\CompetenceMaitriseService;
use Application\Service\CompetenceTheme\CompetenceThemeService;
use Application\Service\CompetenceType\CompetenceTypeService;
use Application\Service\FicheMetier\FicheMetierService;
use Interop\Container\ContainerInterface;

class CompetenceControllerFactory
{

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ActiviteService $activiteService
         * @var AgentService $agentService
         * @var CompetenceService $competenceService
         * @var CompetenceMaitriseService $competenceMaitriseService
         * @var CompetenceThemeService $competenceThemeService
         * @var CompetenceTypeService $competenceTypeService
         * @var CompetenceElementService $competenceElementService
         * @var FicheMetierService $ficherMetierService
         */
        $activiteService = $container->get(ActiviteService::class);
        $agentService = $container->get(AgentService::class);
        $competenceService = $container->get(CompetenceService::class);
        $competenceMaitriseService = $container->get(CompetenceMaitriseService::class);
        $competenceThemeService = $container->get(CompetenceThemeService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $ficherMetierService = $container->get(FicheMetierService::class);

        /**
         * @var CompetenceForm $competenceForm
         * @var CompetenceElementForm $competenceElementForm
         * @var CompetenceTypeForm $competenceTypeForm
         * @var ModifierLibelleForm $modifierLibelleForm
         * @var SelectionCompetenceForm $selectionCompetenceForm
         * @var SelectionCompetenceMaitriseForm $selectionCompetenceMaitriseForm
         */
        $competenceForm = $container->get('FormElementManager')->get(CompetenceForm::class);
        $competenceElementForm = $container->get('FormElementManager')->get(CompetenceElementForm::class);
        $competenceTypeForm = $container->get('FormElementManager')->get(CompetenceTypeForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);
        $selectionCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);
        $selectionCompetenceMaitriseForm = $container->get('FormElementManager')->get(SelectionCompetenceMaitriseForm::class);

        /** @var CompetenceController $controller */
        $controller = new CompetenceController();
        $controller->setActiviteService($activiteService);
        $controller->setAgentService($agentService);
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceMaitriseService($competenceMaitriseService);
        $controller->setCompetenceThemeService($competenceThemeService);
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setFicheMetierService($ficherMetierService);
        $controller->setCompetenceForm($competenceForm);
        $controller->setCompetenceElementForm($competenceElementForm);
        $controller->setCompetenceTypeForm($competenceTypeForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        $controller->setSelectionCompetenceForm($selectionCompetenceForm);
        $controller->setSelectionCompetenceMaitriseForm($selectionCompetenceMaitriseForm);
        return $controller;
    }
}