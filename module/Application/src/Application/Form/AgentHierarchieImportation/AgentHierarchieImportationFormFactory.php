<?php

namespace Application\Form\AgentHierarchieImportation;

use Agent\Service\AgentRef\AgentRefService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentHierarchieImportationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentHierarchieImportationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentHierarchieImportationForm
    {
        /**
         * @var AgentRefService $agentRefService
         * @var AgentHierarchieImportationHydrator $hydrator
         */
        $agentRefService = $container->get(AgentRefService::class);
        $hydrator = $container->get('HydratorManager')->get(AgentHierarchieImportationHydrator::class);

        $form = new AgentHierarchieImportationForm();
        $form->setAgentRefService($agentRefService);
        $form->setHydrator($hydrator);
        return $form;
    }
}