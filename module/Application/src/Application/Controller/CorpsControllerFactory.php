<?php

namespace Application\Controller;

use Application\Form\ModifierNiveau\ModifierNiveauForm;
use Application\Service\Agent\AgentService;
use Application\Service\Categorie\CategorieService;
use Application\Service\Corps\CorpsService;
use Application\Service\Correspondance\CorrespondanceService;
use Application\Service\Grade\GradeService;
use Application\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class CorpsControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return CorpsController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CategorieService $categorieService
         * @var CorpsService $corpsService
         * @var CorrespondanceService $correspondanceService
         * @var GradeService $gradeService
         * @var NiveauService $niveauService
         */
        $categorieService = $container->get(CategorieService::class);
        $corpsService = $container->get(CorpsService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $gradeService = $container->get(GradeService::class);
        $niveauService = $container->get(NiveauService::class);

        /**
         * @var ModifierNiveauForm $modifierNiveauForm
         */
        $modifierNiveauForm = $container->get('FormElementManager')->get(ModifierNiveauForm::class);

        /** @var CorpsController $controller */
        $controller = new CorpsController();
        $controller->setCategorieService($categorieService);
        $controller->setCorpsService($corpsService);
        $controller->setCorrespondanceService($correspondanceService);
        $controller->setGradeService($gradeService);
        $controller->setNiveauService($niveauService);
        $controller->setModifierNiveauForm($modifierNiveauForm);
        return $controller;
    }
}