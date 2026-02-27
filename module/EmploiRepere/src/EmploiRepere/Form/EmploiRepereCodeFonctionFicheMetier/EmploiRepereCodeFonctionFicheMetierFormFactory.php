<?php

namespace EmploiRepere\Form\EmploiRepereCodeFonctionFicheMetier;

use EmploiRepere\Service\EmploiRepere\EmploiRepereService;
use EmploiRepere\Service\EmploiRepereCodeFonctionFicheMetier\EmploiRepereCodeFonctionFicheMetierService;
use FicheMetier\Service\CodeFonction\CodeFonctionService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class EmploiRepereCodeFonctionFicheMetierFormFactory
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EmploiRepereCodeFonctionFicheMetierForm
    {
        /**
         * @var EmploiRepereService $emploiRepereService
         * @var EmploiRepereCodeFonctionFicheMetierService $ercffmService
         * @var CodeFonctionService $codeFonctionService
         * @var FicheMetierService $ficheMetierService
         * @var EmploiRepereCodeFonctionFicheMetierHydrator $hydrator
         */
        $emploiRepereService = $container->get(EmploiRepereService::class);
        $ercffmService = $container->get(EmploiRepereCodeFonctionFicheMetierService::class);
        $codeFonctionService = $container->get(CodeFonctionService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $hydrator = $container->get('HydratorManager')->get(EmploiRepereCodeFonctionFicheMetierHydrator::class);

        $form = new EmploiRepereCodeFonctionFicheMetierForm();
        $form->setHydrator($hydrator);
        $form->setEmploiRepereService($emploiRepereService);
        $form->setEmploiRepereCodeFonctionFicheMetierService($ercffmService);
        $form->setCodeFonctionService($codeFonctionService);
        $form->setFicheMetierService($ficheMetierService);
        return $form;
    }
}
