<?php

namespace Application\Form\AgentStageObservation;

use Interop\Container\ContainerInterface;
use Metier\Service\Metier\MetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;

class AgentStageObservationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentStageObservationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentStageObservationForm
    {
        /**
         * @var StructureService $structureService
         * @var MetierService $metierService
         */
        $structureService = $container->get(StructureService::class);
        $metierService = $container->get(MetierService::class);

        /** @var AgentStageObservationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentStageObservationHydrator::class);

        $form = new AgentStageObservationForm();
        $form->setStructureService($structureService);
        $form->setMetierService($metierService);
        $form->setHydrator($hydrator);
        return $form;
    }
}