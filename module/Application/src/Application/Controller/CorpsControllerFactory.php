<?php

namespace Application\Controller;

use Application\Form\ModifierNiveau\ModifierNiveauForm;
use Application\Service\Agent\AgentService;
use Application\Service\Categorie\CategorieService;
use Application\Service\Corps\CorpsService;
use Application\Service\Correspondance\CorrespondanceService;
use Application\Service\Grade\GradeService;
use Interop\Container\ContainerInterface;

class CorpsControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return CorpsController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var CategorieService $categorieService
         * @var CorpsService $corpsService
         * @var CorrespondanceService $correspondanceService
         * @var GradeService $gradeService
         */
        $agentService = $container->get(AgentService::class);
        $categorieService = $container->get(CategorieService::class);
        $corpsService = $container->get(CorpsService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $gradeService = $container->get(GradeService::class);

        /**
         * @var ModifierNiveauForm $modifierNiveauForm
         */
        $modifierNiveauForm = $container->get('FormElementManager')->get(ModifierNiveauForm::class);

        /** @var CorpsController $controller */
        $controller = new CorpsController();
        $controller->setAgentService($agentService);
        $controller->setCategorieService($categorieService);
        $controller->setCorpsService($corpsService);
        $controller->setCorrespondanceService($correspondanceService);
        $controller->setGradeService($gradeService);
        $controller->setModifierNiveauForm($modifierNiveauForm);
        return $controller;
    }
}