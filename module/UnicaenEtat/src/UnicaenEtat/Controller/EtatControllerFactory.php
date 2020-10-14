<?php

namespace UnicaenEtat\Controller;

use Interop\Container\ContainerInterface;
use UnicaenEtat\Form\Etat\EtatForm;
use UnicaenEtat\Service\Etat\EtatService;

class EtatControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return EtatController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EtatService $etatTypeService
         */
        $etatService = $container->get(EtatService::class);

        /**
         * @var EtatForm $etatTypeForm
         */
        $etatForm = $container->get('FormElementManager')->get(EtatForm::class);

        $controller = new EtatController();
        $controller->setEtatService($etatService);
        $controller->setEtatForm($etatForm);
        return $controller;
    }

}