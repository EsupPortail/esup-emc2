<?php

namespace Formation\Service\FormationInstanceJournee;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class FormationInstanceJourneeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceJourneeService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var FormationInstanceJourneeService $service */
        $service = new FormationInstanceJourneeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}