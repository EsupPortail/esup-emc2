<?php

namespace Application\Controller\Fonction;

use Application\Form\Fonction\FonctionForm;
use Application\Service\Fonction\FonctionService;
use Zend\Mvc\Controller\ControllerManager;

class FonctionControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /** @var FonctionService $fonctionService */
        $fonctionService = $manager->getServiceLocator()->get(FonctionService::class);

        /** @var FonctionForm $fonctionForm */
        $fonctionForm = $manager->getServiceLocator()->get('FormElementManager')->get(FonctionForm::class);

        /** @var FonctionController $controller */
        $controller = new FonctionController();
        $controller->setFonctionService($fonctionService);
        $controller->setFonctionForm($fonctionForm);
        return $controller;
    }
}