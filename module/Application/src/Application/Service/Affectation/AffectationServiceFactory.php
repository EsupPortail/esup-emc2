<?php

namespace Application\Service\Affectation;

use Application\Service\User\UserService;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class AffectationServiceFactory {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AffectationService
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var AffectationService $service */
        $service = new AffectationService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}