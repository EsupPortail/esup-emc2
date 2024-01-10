<?php

namespace MissionSpecifique\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use MissionSpecifique\Form\MissionSpecifique\MissionSpecifiqueForm;
use MissionSpecifique\Service\MissionSpecifique\MissionSpecifiqueService;
use MissionSpecifique\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeService;
use MissionSpecifique\Service\MissionSpecifiqueType\MissionSpecifiqueTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionSpecifiqueControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MissionSpecifiqueController
    {
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

        $controller = new MissionSpecifiqueController();
        $controller->setMissionSpecifiqueService($missionService);
        $controller->setMissionSpecifiqueThemeService($missionThemeService);
        $controller->setMissionSpecifiqueTypeService($missionTypeService);
        $controller->setMissionSpecifiqueForm($missionSpecifiqueForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        return $controller;
    }
}