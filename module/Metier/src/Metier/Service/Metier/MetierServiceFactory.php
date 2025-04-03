<?php

namespace Metier\Service\Metier;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Metier\Entity\Db\Reference;
use Metier\Service\Domaine\DomaineService;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Metier\Service\Reference\ReferenceService;
use Metier\Service\Referentiel\ReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class MetierServiceFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MetierService
    {
        /**
         * @var EntityManager $entityManager
         * @var DomaineService $domaineService
         * @var FamilleProfessionnelleService $familleService
         * @var ReferenceService $referenceService
         * @var ReferentielService $referentielService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $domaineService = $container->get(DomaineService::class);
        $familleService = $container->get(FamilleProfessionnelleService::class);
        $referenceService = $container->get(ReferenceService::class);
        $referentielService = $container->get(ReferentielService::class);

        $service = new MetierService();
        $service->setObjectManager($entityManager);
        $service->setDomaineService($domaineService);
        $service->setFamilleProfessionnelleService($familleService);
        $service->setReferenceService($referenceService);
        $service->setReferentielService($referentielService);
        return $service;
    }
}