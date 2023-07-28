<?php

namespace Application\Form\AgentStageObservation;

use Interop\Container\ContainerInterface;
use Metier\Service\Metier\MetierService;
use Structure\Service\Structure\StructureService;
use UnicaenEtat\src\UnicaenEtat\Service\Etat\EtatService;

class AgentStageObservationHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentStageObservationHydrator
     */
    public function __invoke(ContainerInterface $container) : AgentStageObservationHydrator
    {
        /**
         * @var StructureService $structureService
         * @var MetierService $metierService
         * @var UnicaenEtat\src\UnicaenEtat\Service\Etat\EtatService $etatService
         */
        $structureService = $container->get(StructureService::class);
        $metierService = $container->get(MetierService::class);
        $etatService = $container->get(EtatService::class);

        $hydrator = new AgentStageObservationHydrator();
        $hydrator->setStructureService($structureService);
        $hydrator->setMetierService($metierService);
        $hydrator->setEtatService($etatService);
        return $hydrator;
    }
}