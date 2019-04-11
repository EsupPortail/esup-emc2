<?php

namespace Application\Controller\Poste;

use Application\Form\Poste\PosteForm;
use Application\Service\Poste\PosteService;
use Octopus\Service\Immobilier\ImmobilierService;
use Zend\Mvc\Controller\ControllerManager;

class PosteControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var PosteService $posteService
         * @var ImmobilierService $immobilierService
         */
        $posteService    = $manager->getServiceLocator()->get(PosteService::class);
        $immobilierService    = $manager->getServiceLocator()->get(ImmobilierService::class);

        /**
         * @var PosteForm $posteForm
         */
        $posteForm = $manager->getServiceLocator()->get('FormElementManager')->get(PosteForm::class);

        /** @var PosteController $controller */
        $controller = new PosteController();
        $controller->setPosteService($posteService);
        $controller->setImmobiliserService($immobilierService);
        $controller->setPosteForm($posteForm);
        return $controller;
    }
}