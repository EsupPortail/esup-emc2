<?php

namespace Application\Form\MissionSpecifique;

use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var MissionSpecifiqueService $missionSpecifiqueService  */
        $missionSpecifiqueService = $container->get(MissionSpecifiqueService::class);

        /** @var MissionSpecifiqueHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(MissionSpecifiqueHydrator::class);

        /** @var MissionSpecifiqueForm $form */
        $form = new MissionSpecifiqueForm();
        $form->setMissionSpecifiqueService($missionSpecifiqueService);
        $form->setHydrator($hydrator);
        return $form;
    }
}