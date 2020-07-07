<?php

namespace Application\Service\ParcoursDeFormation;

use Application\Service\Metier\MetierService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ParcoursDeFormationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ParcoursDeFormationService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var MetierService $metierService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $metierService = $container->get(MetierService::class);

        /** @var ParcoursDeFormationService $service */
        $service = new ParcoursDeFormationService();
        $service->setEntityManager($entityManager);
        $service->setMetierService($metierService);
        return $service;
    }
}