<?php

namespace Application\Controller;

use Application\Form\Competence\CompetenceForm;
use Application\Form\CompetenceType\CompetenceTypeForm;
use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Service\Competence\CompetenceService;
use Application\Service\CompetenceTheme\CompetenceThemeService;
use Application\Service\CompetenceType\CompetenceTypeService;
use Interop\Container\ContainerInterface;

class CompetenceControllerFactory
{

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceThemeService $competenceThemeService
         * @var CompetenceTypeService $competenceTypeService
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceThemeService = $container->get(CompetenceThemeService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);

        /**
         * @var CompetenceForm $competenceForm
         * @var CompetenceTypeForm $competenceTypeForm
         * @var ModifierLibelleForm $modifierLibelleForm
         */
        $competenceForm = $container->get('FormElementManager')->get(CompetenceForm::class);
        $competenceTypeForm = $container->get('FormElementManager')->get(CompetenceTypeForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);

        /** @var CompetenceController $controller */
        $controller = new CompetenceController();
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceThemeService($competenceThemeService);
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setCompetenceForm($competenceForm);
        $controller->setCompetenceTypeForm($competenceTypeForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        return $controller;
    }
}