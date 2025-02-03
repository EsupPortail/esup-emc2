<?php

namespace UnicaenContact\Form\Contact;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
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
         * @var TypeService $typeService
         */
        $typeService = $container->get(TypeService::class);

        $hydrator = new ContactHydrator();
        $hydrator->setTypeService($typeService);
        return $hydrator;
    }
}