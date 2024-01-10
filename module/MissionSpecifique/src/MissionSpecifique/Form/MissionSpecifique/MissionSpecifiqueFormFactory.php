<?php

namespace MissionSpecifique\Form\MissionSpecifique;

use MissionSpecifique\Service\MissionSpecifique\MissionSpecifiqueService;
use MissionSpecifique\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeService;
use MissionSpecifique\Service\MissionSpecifiqueType\MissionSpecifiqueTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionSpecifiqueFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MissionSpecifiqueForm
    {
        /**
         * @var MissionSpecifiqueService $missionSpecifiqueService
         * @var MissionSpecifiqueThemeService $missionSpecifiqueThemeService
         * @var MissionSpecifiqueTypeService $missionSpecifiqueTypeService
         */
        $missionSpecifiqueService = $container->get(MissionSpecifiqueService::class);
        $missionSpecifiqueThemeService = $container->get(MissionSpecifiqueThemeService::class);
        $missionSpecifiqueTypeService = $container->get(MissionSpecifiqueTypeService::class);

        /** @var MissionSpecifiqueHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(MissionSpecifiqueHydrator::class);

        $form = new MissionSpecifiqueForm();
        $form->setMissionSpecifiqueService($missionSpecifiqueService);
        $form->setMissionSpecifiqueThemeService($missionSpecifiqueThemeService);
        $form->setMissionSpecifiqueTypeService($missionSpecifiqueTypeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}