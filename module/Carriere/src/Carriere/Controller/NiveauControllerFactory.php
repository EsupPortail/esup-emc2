<?php

namespace Carriere\Controller;

use Carriere\Form\Niveau\NiveauForm;
use Carriere\Service\Niveau\NiveauService;
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
         * @var NiveauService $niveauService
         * @var NiveauForm $niveauForm
         */
        $niveauService = $container->get(NiveauService::class);
        $niveauForm = $container->get('FormElementManager')->get(NiveauForm::class);

        $controller = new NiveauController();
        $controller->setNiveauService($niveauService);
        $controller->setNiveauForm($niveauForm);
        return $controller;
    }
}