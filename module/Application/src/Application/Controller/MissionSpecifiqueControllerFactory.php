<?php

namespace Application\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Form\MissionSpecifique\MissionSpecifiqueForm;
use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Application\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeService;
use Application\Service\MissionSpecifiqueType\MissionSpecifiqueTypeService;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueControllerFactory {

    public function __invoke(ContainerInterface $container) {

        /**
         * @var MissionSpecifiqueService $missionService
         * @var MissionSpecifiqueThemeService $missionThemeService
         * @var MissionSpecifiqueTypeService $missionTypeService
         */
        $missionService = $container->get(MissionSpecifiqueService::class);
        $missionThemeService = $container->get(MissionSpecifiqueThemeService::class);
        $missionTypeService = $container->get(MissionSpecifiqueTypeService::class);

        /**
         * @var MissionSpecifiqueForm $missionSpecifiqueForm
         * @var ModifierLibelleForm $modifierLibelleForm
         */
        $missionSpecifiqueForm = $container->get('FormElementManager')->get(MissionSpecifiqueForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);

        /** @var MissionSpecifiqueController $controller */
        $controller = new MissionSpecifiqueController();
        $controller->setMissionSpecifiqueService($missionService);
        $controller->setMissionSpecifiqueThemeService($missionThemeService);
        $controller->setMissionSpecifiqueTypeService($missionTypeService);
        $controller->setMissionSpecifiqueForm($missionSpecifiqueForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        return $controller;
    }
}