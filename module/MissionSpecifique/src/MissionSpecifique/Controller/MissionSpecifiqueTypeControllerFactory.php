<?php

namespace MissionSpecifique\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use MissionSpecifique\Service\MissionSpecifiqueType\MissionSpecifiqueTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionSpecifiqueTypeControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MissionSpecifiqueTypeController
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