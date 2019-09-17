<?php

namespace Application\Form\RessourceRh;

use Application\Service\RessourceRh\RessourceRhService;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var RessourceRhService $ressourceService  */
        $ressourceService = $container->get(RessourceRhService::class);

        /** @var MissionSpecifiqueHydrator $hydrator */
        $hydrator = new MissionSpecifiqueHydrator();
        $hydrator->setRessourceRhService($ressourceService);
        return $hydrator;
    }
}