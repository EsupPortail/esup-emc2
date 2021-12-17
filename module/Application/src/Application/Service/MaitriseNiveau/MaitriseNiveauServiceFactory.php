<?php

namespace Application\Service\MaitriseNiveau;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class MaitriseNiveauServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return MaitriseNiveauService
     */
    public function __invoke(ContainerInterface $container) : MaitriseNiveauService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new MaitriseNiveauService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}