<?php

namespace Element\Controller;

use Element\Form\Niveau\NiveauForm;
use Element\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NiveauControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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