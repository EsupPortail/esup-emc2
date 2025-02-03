<?php

namespace UnicaenContact\Form\Type;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenContact\Service\Type\TypeService;

class TypeFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : TypeForm
    {
        /**
         * @var TypeService $typeService
         * @var TypeHydrator $hydrator
         */
        $typeService = $container->get(TypeService::class);
        $hydrator = $container->get('HydratorManager')->get(TypeHydrator::class);

        $form = new TypeForm();
        $form->setHydrator($hydrator);
        $form->setTypeService($typeService);
        return $form;
    }
}