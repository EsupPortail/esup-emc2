<?php

namespace Application\Service\Poste;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class PosteServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return PosteService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var PosteService $service */
        $service = new PosteService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}