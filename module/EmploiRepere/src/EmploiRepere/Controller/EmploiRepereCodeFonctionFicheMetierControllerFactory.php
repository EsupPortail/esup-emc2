<?php

namespace EmploiRepere\Controller;

use EmploiRepere\Form\EmploiRepereCodeFonctionFicheMetier\EmploiRepereCodeFonctionFicheMetierForm;
use EmploiRepere\Service\EmploiRepere\EmploiRepereService;
use EmploiRepere\Service\EmploiRepereCodeFonctionFicheMetier\EmploiRepereCodeFonctionFicheMetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class EmploiRepereCodeFonctionFicheMetierControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EmploiRepereCodeFonctionFicheMetierController
    {
        /**
         * @var EmploiRepereService $emploiRepereService
         * @var EmploiRepereCodeFonctionFicheMetierService $emploiRepereCodeFonctionFicheMetierService
         * @var EmploiRepereCodeFonctionFicheMetierForm $emploiRepereCodeFonctionFicheMetierForm
         */
        $emploiRepereService = $container->get(EmploiRepereService::class);
        $emploiRepereCodeFonctionFicheMetierService = $container->get(EmploiRepereCodeFonctionFicheMetierService::class);
        $emploiRepereCodeFonctionFicheMetierForm = $container->get('FormElementManager')->get(EmploiRepereCodeFonctionFicheMetierForm::class);

        $controller = new EmploiRepereCodeFonctionFicheMetierController();
        $controller->setEmploiRepereService($emploiRepereService);
        $controller->setEmploiRepereCodeFonctionFicheMetierService($emploiRepereCodeFonctionFicheMetierService);
        $controller->setEmploiRepereCodeFonctionFicheMetierForm($emploiRepereCodeFonctionFicheMetierForm);
        return $controller;
    }
}
