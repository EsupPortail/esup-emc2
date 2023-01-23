<?php

namespace Application\Service\Poste;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class PosteServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return PosteService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : PosteService
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new PosteService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}