<?php

namespace Formation\Service\FormationInstanceFormateur;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class FormationInstanceFormateurServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceFormateurService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var FormationInstanceFormateurService $service */
        $service = new FormationInstanceFormateurService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}