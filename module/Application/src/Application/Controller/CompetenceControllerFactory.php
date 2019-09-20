<?php

namespace Application\Controller;

use Application\Form\CompetenceTheme\CompetenceThemeForm;
use Application\Form\CompetenceType\CompetenceTypeForm;
use Application\Service\Competence\CompetenceService;
use Interop\Container\ContainerInterface;

class CompetenceControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CompetenceService $competenceService
         */
        $competenceService = $container->get(CompetenceService::class);

        /**
         * @var CompetenceThemeForm $competenceThemeForm
         * @var CompetenceTypeForm $competenceTypeForm
         */
        $competenceThemeForm = $container->get('FormElementManager')->get(CompetenceThemeForm::class);
        $competenceTypeForm = $container->get('FormElementManager')->get(CompetenceTypeForm::class);

        /** @var CompetenceController $controller */
        $controller = new CompetenceController();
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceThemeForm($competenceThemeForm);
        $controller->setCompetenceTypeForm($competenceTypeForm);
        return $controller;
    }
}