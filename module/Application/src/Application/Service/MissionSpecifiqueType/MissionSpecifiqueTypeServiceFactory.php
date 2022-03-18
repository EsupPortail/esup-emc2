<?php

namespace Application\Service\MissionSpecifiqueType;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueTypeServiceFactory {

    public function __invoke(ContainerInterface $container) : MissionSpecifiqueTypeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new MissionSpecifiqueTypeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}