<?php

namespace Application\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Form\RessourceRh\MissionSpecifiqueForm;
use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueControllerFactory {

    public function __invoke(ContainerInterface $container) {

        /**
         * @var MissionSpecifiqueService $missionService
         */
        $missionService = $container->get(MissionSpecifiqueService::class);

        /**
         * @var MissionSpecifiqueForm $missionSpecifiqueForm
         * @var ModifierLibelleForm $modifierLibelleForm
         */
        $missionSpecifiqueForm = $container->get('FormElementManager')->get(MissionSpecifiqueForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);


        /** @var MissionSpecifiqueController $controller */
        $controller = new MissionSpecifiqueController();
        $controller->setMissionSpecifiqueService($missionService);

        $controller->setMissionSpecifiqueForm($missionSpecifiqueForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        return $controller;
    }
}