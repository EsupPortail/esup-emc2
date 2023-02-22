<?php

namespace FicheMetier\Controller;

use Application\Form\FicheMetierImportation\FicheMetierImportationForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\FicheMetier\FicheMetierService;
use FicheMetier\Form\Raison\RaisonForm;
use Metier\Form\SelectionnerMetier\SelectionnerMetierForm;
use Metier\Service\Metier\MetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatForm;

class FicheMetierControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheMetierController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FicheMetierController
    {
        /**
         * @var ActiviteService $activiteService
         * @var FicheMetierService $ficheMetierService
         * @var MetierService $metierService
         */
        $activiteService = $container->get(ActiviteService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $metierService = $container->get(metierService::class);

        /**
         * @var FicheMetierImportationForm $importationForm
         * @var RaisonForm $raisonForm
         * @var SelectionEtatForm $selectionnerEtatForm
         * @var SelectionnerMetierForm $selectionnerMetierForm
         */
        $importationForm = $container->get('FormElementManager')->get(FicheMetierImportationForm::class);
        $selectionnerEtatForm = $container->get('FormElementManager')->get(SelectionEtatForm::class);
        $selectionnerMetierForm = $container->get('FormElementManager')->get(SelectionnerMetierForm::class);
        $raisonForm = $container->get('FormElementManager')->get(RaisonForm::class);

        $controller = new FicheMetierController();
        $controller->setActiviteService($activiteService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setMetierService($metierService);
        $controller->setFicheMetierImportationForm($importationForm);
        $controller->setRaisonForm($raisonForm);
        $controller->setSelectionEtatForm($selectionnerEtatForm);
        $controller->setSelectionnerMetierForm($selectionnerMetierForm);
        return $controller;
    }
}