<?php

namespace Fichier\Controller;

use Fichier\Form\Upload\UploadForm;
use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Nature\NatureService;
use Zend\Mvc\Controller\ControllerManager;

class FichierControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var NatureService $natureService
         * @var FichierService $fichierService
         */
        $natureService = $manager->getServiceLocator()->get(NatureService::class);
        $fichierService = $manager->getServiceLocator()->get(FichierService::class);

        /**
         * @var UploadForm $uploadForm
         */
        $uploadForm = $manager->getServiceLocator()->get('FormElementManager')->get(UploadForm::class);

        /** @var FichierController $controller */
        $controller = new FichierController();
        $controller->setNatureService($natureService);
        $controller->setFichierService($fichierService);
        $controller->setUploadForm($uploadForm);
        return $controller;
    }
}