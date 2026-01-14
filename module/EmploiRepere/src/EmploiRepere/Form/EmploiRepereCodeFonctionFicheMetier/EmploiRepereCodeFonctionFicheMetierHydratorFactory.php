<?php

namespace EmploiRepere\Form\EmploiRepereCodeFonctionFicheMetier;

use EmploiRepere\Service\EmploiRepere\EmploiRepereService;
use FicheMetier\Service\CodeFonction\CodeFonctionService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class EmploiRepereCodeFonctionFicheMetierHydratorFactory
{

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EmploiRepereCodeFonctionFicheMetierHydrator
    {
        /**
         * @var EmploiRepereService $emploiRepereService
         * @var CodeFonctionService $codeFonctionService
         * @var FicheMetierService $ficheMetierService
         */
        $emploiRepereService = $container->get(EmploiRepereService::class);
        $codeFonctionService = $container->get(CodeFonctionService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);

        $hydrator = new EmploiRepereCodeFonctionFicheMetierHydrator();
        $hydrator->setEmploiRepereService($emploiRepereService);
        $hydrator->setCodeFonctionService($codeFonctionService);
        $hydrator->setFicheMetierService($ficheMetierService);
        return $hydrator;
    }
}
