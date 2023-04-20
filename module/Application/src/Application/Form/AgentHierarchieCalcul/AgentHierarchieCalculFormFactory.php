<?php

namespace Application\Form\AgentHierarchieCalcul;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;

class AgentHierarchieCalculFormFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentHierarchieCalculForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentHierarchieCalculForm
    {
        /** @var StructureService $structureService */
        $structureService = $container->get(StructureService::class);
        /** @var AgentHierarchieCalculHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentHierarchieCalculHydrator::class);

        $form = new AgentHierarchieCalculForm();
        $form->setStructureService($structureService);
        $form->setHydrator($hydrator);
        return $form;
    }
}