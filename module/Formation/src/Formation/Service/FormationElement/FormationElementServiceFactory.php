<?php

namespace Formation\Service\FormationElement;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class FormationElementServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationElementService
     */
    public function __invoke(ContainerInterface $container) : FormationElementService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new FormationElementService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}