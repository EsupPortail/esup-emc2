<?php

namespace Formation\Service\FormationInstanceFrais;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormationInstanceFraisServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceFraisService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationInstanceFraisService
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