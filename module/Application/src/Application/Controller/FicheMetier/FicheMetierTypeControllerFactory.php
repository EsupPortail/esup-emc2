<?php

namespace Application\Controller\FicheMetier;

use Application\Form\Activite\ActiviteForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\FicheMetier\FicheMetierService;
use Zend\Mvc\Controller\ControllerManager;

class FicheMetierTypeControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var ActiviteService $activiteService
         * @var FicheMetierService $ficheMetierService
         */
        $activiteService = $manager->getServiceLocator()->get(ActiviteService::class);
        $ficheMetierService = $manager->getServiceLocator()->get(FicheMetierService::class);

        /**
         * @var ActiviteForm $activiteForm
         */
        $activiteForm = $manager->getServiceLocator()->get('FormElementManager')->get(ActiviteForm::class);


        /** @var FicheMetierControllerFactory.php $controller */
        $controller = new FicheMetierTypeController();
        $controller->setActiviteService($activiteService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setActiviteForm($activiteForm);
        return $controller;
    }

}