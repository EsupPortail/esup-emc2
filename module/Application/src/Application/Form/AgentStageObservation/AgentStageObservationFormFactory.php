<?php

namespace Application\Form\AgentStageObservation;

use Interop\Container\ContainerInterface;
use Metier\Service\Metier\MetierService;
use Structure\Service\Structure\StructureService;
use UnicaenEtat\Service\Etat\EtatService;

class AgentStageObservationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentStageObservationForm
     */
    public function __invoke(ContainerInterface $container) : AgentStageObservationForm
    {
        /**
         * @var StructureService $structureService
         * @var MetierService $metierService
         * @var EtatService $etatService
         */
        $structureService = $container->get(StructureService::class);
        $metierService = $container->get(MetierService::class);
        $etatService = $container->get(EtatService::class);

        /** @var AgentStageObservationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentStageObservationHydrator::class);

        $form = new AgentStageObservationForm();
        $form->setStructureService($structureService);
        $form->setMetierService($metierService);
        $form->setEtatService($etatService);
        $form->setHydrator($hydrator);
        return $form;
    }
}