<?php

namespace Formation\Service\FormationInstancePresence;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class FormationInstancePresenceServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return FormationInstancePresenceService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var FormationInstancePresenceService $service */
        $service = new FormationInstancePresenceService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}