<?php

namespace Formation\Service\FormationInstanceFrais;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class FormationInstanceFraisServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceFraisService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var FormationInstanceFraisService $service */
        $service = new FormationInstanceFraisService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}