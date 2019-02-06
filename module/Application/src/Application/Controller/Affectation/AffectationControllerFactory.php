<?php

namespace Application\Controller\Affectation;

use Application\Form\Affectation\AffectationForm;
use Application\Service\Affectation\AffectationService;
use Zend\Mvc\Controller\ControllerManager;

class AffectationControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /** @var AffectationService $affectationService */
        $affectationService = $manager->getServiceLocator()->get(AffectationService::class);

        /** @var AffectationForm $affectationForm */
        $affectationForm = $manager->getServiceLocator()->get('FormElementManager')->get(AffectationForm::class);

        /** @var AffectationController $controller */
        $controller = new AffectationController();
        $controller->setAffectationService($affectationService);
        $controller->setAffectationForm($affectationForm);
        return $controller;
    }
}