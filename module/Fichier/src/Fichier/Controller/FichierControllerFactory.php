<?php

namespace Fichier\Controller;

use Fichier\Form\Upload\UploadForm;
use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Nature\NatureService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FichierControllerFactory {

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FichierController
    {
        /**
         * @var NatureService $natureService
         * @var FichierService $fichierService
         */
        $natureService = $container->get(NatureService::class);
        $fichierService = $container->get(FichierService::class);

        /**
         * @var UploadForm $uploadForm
         */
        $uploadForm = $container->get('FormElementManager')->get(UploadForm::class);

        $controller = new FichierController();
        $controller->setNatureService($natureService);
        $controller->setFichierService($fichierService);
        $controller->setUploadForm($uploadForm);
        return $controller;
    }
}