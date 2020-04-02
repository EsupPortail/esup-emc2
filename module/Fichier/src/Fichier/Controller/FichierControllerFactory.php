<?php

namespace Fichier\Controller;

use Fichier\Form\Upload\UploadForm;
use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Nature\NatureService;
use Interop\Container\ContainerInterface;

class FichierControllerFactory {

    public function __invoke(ContainerInterface $container)
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

        /** @var FichierController $controller */
        $controller = new FichierController();
        $controller->setNatureService($natureService);
        $controller->setFichierService($fichierService);
        $controller->setUploadForm($uploadForm);
        return $controller;
    }
}