<?php

namespace Application\Form\AgentStageObservation;

use Interop\Container\ContainerInterface;
use Metier\Service\Metier\MetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class AgentStageObservationHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentStageObservationHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentStageObservationHydrator
    {
        /**
         * @var StructureService $structureService
         * @var MetierService $metierService
         * @var EtatTypeService $etatTypeService
         */
        $etatTypeService = $container->get(EtatTypeService::class);
        $structureService = $container->get(StructureService::class);
        $metierService = $container->get(MetierService::class);

        $hydrator = new AgentStageObservationHydrator();
        $hydrator->setEtatTypeService($etatTypeService);
        $hydrator->setStructureService($structureService);
        $hydrator->setMetierService($metierService);
        return $hydrator;
    }
}