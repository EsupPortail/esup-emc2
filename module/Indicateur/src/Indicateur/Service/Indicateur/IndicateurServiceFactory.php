<?php

namespace Indicateur\Service\Indicateur;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndicateurServiceFactory {

    public function __invoke(ServiceLocatorInterface $manager)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $manager->get('doctrine.entitymanager.orm_default');

        /** @var IndicateurService $service */
        $service = new IndicateurService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}