<?php

namespace FicheMetier\Controller;

use Metier\Service\Domaine\DomaineService;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ImportControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ImportController
    {
        /**
         * @var DomaineService $domaineService
         * @var FamilleProfessionnelleService $familleProfessionnelService
         */
        $domaineService = $container->get(DomaineService::class);
        $familleProfessionnelService = $container->get(FamilleProfessionnelleService::class);

        $controller = new ImportController();
        $controller->setDomaineService($domaineService);
        $controller->setFamilleProfessionnelleService($familleProfessionnelService);
        return $controller;
    }

}