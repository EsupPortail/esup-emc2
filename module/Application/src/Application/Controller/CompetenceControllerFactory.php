<?php

namespace Application\Controller;

use Application\Form\Competence\CompetenceForm;
use Application\Form\CompetenceType\CompetenceTypeForm;
use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Form\SelectionCompetence\SelectionCompetenceForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\Competence\CompetenceService;
use Application\Service\CompetenceElement\CompetenceElementService;
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
         * @var CompetenceService $competenceService
         * @var CompetenceThemeService $competenceThemeService
         * @var CompetenceTypeService $competenceTypeService
         * @var CompetenceElementService $competenceElementService
         * @var FicheMetierService $ficherMetierService
         */
        $activiteService = $container->get(ActiviteService::class);
        $competenceService = $container->get(CompetenceService::class);
        $competenceThemeService = $container->get(CompetenceThemeService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $ficherMetierService = $container->get(FicheMetierService::class);

        /**
         * @var CompetenceForm $competenceForm
         * @var CompetenceTypeForm $competenceTypeForm
         * @var ModifierLibelleForm $modifierLibelleForm
         * @var SelectionCompetenceForm $selectionCompetenceForm
         */
        $competenceForm = $container->get('FormElementManager')->get(CompetenceForm::class);
        $competenceTypeForm = $container->get('FormElementManager')->get(CompetenceTypeForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);
        $selectionCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);

        /** @var CompetenceController $controller */
        $controller = new CompetenceController();
        $controller->setActiviteService($activiteService);
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceThemeService($competenceThemeService);
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setFicheMetierService($ficherMetierService);
        $controller->setCompetenceForm($competenceForm);
        $controller->setCompetenceTypeForm($competenceTypeForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        $controller->setSelectionCompetenceForm($selectionCompetenceForm);
        return $controller;
    }
}