<?php

namespace Element\Controller;

use Element\Form\Niveau\NiveauForm;
use Element\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class NiveauControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauController
     */
    public function __invoke(ContainerInterface $container) : NiveauController
    {
        /**
         * @var NiveauService $NiveauService
         * @var NiveauForm $NiveauForm
         */
        $NiveauService = $container->get(NiveauService::class);
        $NiveauForm = $container->get('FormElementManager')->get(NiveauForm::class);

        $controller = new NiveauController();
        $controller->setNiveauService($NiveauService);
        $controller->setNiveauForm($NiveauForm);
        return $controller;
    }
}