<?php

namespace UnicaenEtat\Controller;

use Interop\Container\ContainerInterface;
use UnicaenEtat\Form\EtatType\EtatTypeForm;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class EtatTypeControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EtatTypeService $etatTypeService
         */
        $etatTypeService = $container->get(EtatTypeService::class);

        /**
         * @var EtatTypeForm $etatTypeForm
         */
        $etatTypeForm = $container->get('FormElementManager')->get(EtatTypeForm::class);

        $controller = new EtatTypeController();
        $controller->setEtatTypeService($etatTypeService);
        $controller->setEtatTypeForm($etatTypeForm);
        return $controller;
    }

}