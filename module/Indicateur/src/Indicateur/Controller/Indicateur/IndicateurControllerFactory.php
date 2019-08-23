<?php

namespace Indicateur\Controller\Indicateur;

use Indicateur\Form\Indicateur\IndicateurForm;
use Indicateur\Service\Indicateur\IndicateurService;
use Zend\Mvc\Controller\ControllerManager;

class IndicateurControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var IndicateurService $indicateurService
         */
        $indicateurService = $manager->getServiceLocator()->get(IndicateurService::class);

        /**
         * @var IndicateurForm $indicateurForm
         */
        $indicateurForm = $manager->getServiceLocator()->get('FormElementManager')->get(IndicateurForm::class);

        /** @var IndicateurController $controller */
        $controller = new IndicateurController();
        $controller->setIndicateurService($indicateurService);
        $controller->setIndicateurForm($indicateurForm);
        return $controller;
    }
}