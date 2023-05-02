<?php

namespace Application\Form\AgentHierarchieImportation;

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
        /** @var AgentHierarchieImportationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentHierarchieImportationHydrator::class);

        $form = new AgentHierarchieImportationForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}