<?php

namespace Application\Form\RessourceRh;

use Application\Service\RessourceRh\RessourceRhService;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var RessourceRhService $ressourceService  */
        $ressourceService = $container->get(RessourceRhService::class);

        /** @var MissionSpecifiqueHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(MissionSpecifiqueHydrator::class);

        /** @var MissionSpecifiqueForm $form */
        $form = new MissionSpecifiqueForm();
        $form->setRessourceRhService($ressourceService);
        $form->setHydrator($hydrator);
        return $form;
    }
}