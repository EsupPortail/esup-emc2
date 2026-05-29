<?php

namespace Structure\Form\Contact;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenContact\Service\Type\TypeService;

class  ContactHydratorFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ContactHydrator
    {
        /**
         * @var StructureService $structureService
         * @var TypeService $typeService
         */
        $structureService = $container->get(StructureService::class);
        $typeService = $container->get(TypeService::class);

        $hydrator = new ContactHydrator();
        $hydrator->setStructureService($structureService);
        $hydrator->setTypeService($typeService);
        return $hydrator;
    }
}