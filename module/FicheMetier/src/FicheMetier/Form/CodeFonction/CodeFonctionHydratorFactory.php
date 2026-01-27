<?php

namespace FicheMetier\Form\CodeFonction;

use Carriere\Service\NiveauFonction\NiveauFonctionService;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CodeFonctionHydratorFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CodeFonctionHydrator
    {
        /**
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         * @var NiveauFonctionService $niveauFonctionService
         */
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);
        $niveauFonctionService = $container->get(NiveauFonctionService::class);

        $hydrator = new CodeFonctionHydrator();
        $hydrator->setFamilleProfessionnelleService($familleProfessionnelleService);
        $hydrator->setNiveauFonctionService($niveauFonctionService);
        return $hydrator;
    }
}