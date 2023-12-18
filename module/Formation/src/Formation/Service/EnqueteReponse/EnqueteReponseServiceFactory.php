<?php

namespace Formation\Service\EnqueteReponse;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class EnqueteReponseServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return EnqueteReponseService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : EnqueteReponseService
    {
        /**
         * @var EntityManager $entitymanager
         */
        $entitymanager = $container->get('doctrine.entitymanager.orm_default');

        $service = new EnqueteReponseService();
        $service->setObjectManager($entitymanager);
        return $service;
    }
}