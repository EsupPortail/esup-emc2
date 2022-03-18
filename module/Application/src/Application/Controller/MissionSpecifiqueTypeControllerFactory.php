<?php

namespace Application\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Service\MissionSpecifiqueType\MissionSpecifiqueTypeService;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueTypeControllerFactory {

    public function __invoke(ContainerInterface $container) : MissionSpecifiqueTypeController
    {
        /**
         * @var MissionSpecifiqueTypeService $missionSpecifiqueTypeService
         */
        $missionSpecifiqueTypeService = $container->get(MissionSpecifiqueTypeService::class);

        /**
         * @var ModifierLibelleForm $modifierLibelleForm
         */
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);

        $controller = new MissionSpecifiqueTypeController();
        $controller->setMissionSpecifiqueTypeService($missionSpecifiqueTypeService);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        return $controller;
    }
}