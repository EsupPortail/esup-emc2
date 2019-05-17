<?php

namespace Application\Controller\Poste;

use Application\Form\Poste\PosteForm;
use Application\Service\Poste\PosteService;
use Zend\Mvc\Controller\ControllerManager;

class PosteControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var PosteService $posteService
         */
        $posteService    = $manager->getServiceLocator()->get(PosteService::class);

        /**
         * @var PosteForm $posteForm
         */
        $posteForm = $manager->getServiceLocator()->get('FormElementManager')->get(PosteForm::class);

        /** @var PosteController $controller */
        $controller = new PosteController();
        $controller->setPosteService($posteService);
        $controller->setPosteForm($posteForm);
        return $controller;
    }
}