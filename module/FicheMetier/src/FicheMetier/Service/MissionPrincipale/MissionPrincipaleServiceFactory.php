<?php

namespace FicheMetier\Service\MissionPrincipale;

use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Carriere\Service\Niveau\NiveauService;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionPrincipaleServiceFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MissionPrincipaleService
    {
        /**
         * @var EntityManager $entityManager
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         * @var NiveauService $niveauService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);
        $niveauService = $container->get(NiveauService::class);

        $service = new MissionPrincipaleService();
        $service->setObjectManager($entityManager);
        $service->setFamilleProfessionnelleService($familleProfessionnelleService);
        $service->setNiveauService($niveauService);
        return $service;
    }
}