<?php

namespace Element\Controller;

use Element\Form\Niveau\NiveauForm;
use Element\Service\NiveauMaitrise\NiveauMaitriseService;
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
         * @var NiveauMaitriseService $NiveauMaitriseService
         * @var NiveauForm $NiveauForm
         */
        $NiveauMaitriseService = $container->get(NiveauMaitriseService::class);
        $NiveauForm = $container->get('FormElementManager')->get(NiveauForm::class);

        $controller = new NiveauController();
        $controller->setNiveauMaitriseService($NiveauMaitriseService);
        $controller->setNiveauForm($NiveauForm);
        return $controller;
    }
}