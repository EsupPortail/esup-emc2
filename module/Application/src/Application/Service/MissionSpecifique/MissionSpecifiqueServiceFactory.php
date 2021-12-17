<?php

namespace Application\Service\MissionSpecifique;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueServiceFactory {
    /**
     * @param ContainerInterface $container
     * @return MissionSpecifiqueService
     */
    public function __invoke(ContainerInterface $container) : MissionSpecifiqueService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');


        /** @var MissionSpecifiqueService $service */
        $service = new MissionSpecifiqueService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}