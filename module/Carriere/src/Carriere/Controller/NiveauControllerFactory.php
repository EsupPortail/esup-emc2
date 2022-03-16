<?php

namespace Carriere\Controller;

use Carriere\Form\Niveau\NiveauForm;
use Carriere\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class NiveauControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauController
     */
    public function __invoke(ContainerInterface $container) : NiveauController
    {
        /**
         * @var NiveauService $niveauService
         */
        $niveauService = $container->get(NiveauService::class);

        /**
         * @var NiveauForm $niveauForm
         */
        $niveauForm = $container->get('FormElementManager')->get(NiveauForm::class);

        $controller = new NiveauController();
        $controller->setNiveauService($niveauService);
        $controller->setNiveauForm($niveauForm);
        return $controller;
    }
}