<?php

namespace Application\Form\MissionSpecifique;

use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Application\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeService;
use Application\Service\MissionSpecifiqueType\MissionSpecifiqueTypeService;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueFormFactory {

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

        /** @var MissionSpecifiqueForm $form */
        $form = new MissionSpecifiqueForm();
        $form->setMissionSpecifiqueService($missionSpecifiqueService);
        $form->setMissionSpecifiqueThemeService($missionSpecifiqueThemeService);
        $form->setMissionSpecifiqueTypeService($missionSpecifiqueTypeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}