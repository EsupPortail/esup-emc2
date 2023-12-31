<?php

namespace Application\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeService;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueThemeControllerFactory {

    public function __invoke(ContainerInterface $container) : MissionSpecifiqueThemeController
    {
        /**
         * @var MissionSpecifiqueThemeService $missionSpecifiqueThemeService
         */
        $missionSpecifiqueThemeService = $container->get(MissionSpecifiqueThemeService::class);

        /**
         * @var ModifierLibelleForm $modifierLibelleForm
         */
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);

        $controller = new MissionSpecifiqueThemeController();
        $controller->setMissionSpecifiqueThemeService($missionSpecifiqueThemeService);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        return $controller;
    }
}