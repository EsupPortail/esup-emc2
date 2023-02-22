<?php

namespace FicheMetier\Controller;

use Application\Service\FicheMetier\FicheMetierService;
use FicheMetier\Form\Raison\RaisonForm;
use Metier\Form\SelectionnerMetier\SelectionnerMetierForm;
use Metier\Service\Metier\MetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

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
         * @var FicheMetierService $ficheMetierService
         * @var MetierService $metierService
         */
        $ficheMetierService = $container->get(FicheMetierService::class);
        $metierService = $container->get(metierService::class);

        /**
         * @var RaisonForm $raisonForm
         * @var SelectionnerMetierForm $selectionnerMetierForm
         */
        $selectionnerMetierForm = $container->get('FormElementManager')->get(SelectionnerMetierForm::class);
        $raisonForm = $container->get('FormElementManager')->get(RaisonForm::class);

        $controller = new FicheMetierController();
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setMetierService($metierService);
        $controller->setRaisonForm($raisonForm);
        $controller->setSelectionnerMetierForm($selectionnerMetierForm);
        return $controller;
    }
}