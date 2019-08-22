<?php

namespace Indicateur\Controller\Indicateur;

use Indicateur\Service\Indicateur\IndicateurService;
use Zend\Mvc\Controller\ControllerManager;

class IndicateurControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var IndicateurService $indicateurService
         */
        $indicateurService = $manager->getServiceLocator()->get(IndicateurService::class);

        /** @var IndicateurController $controller */
        $controller = new IndicateurController();
        $controller->setIndicateurService($indicateurService);
        return $controller;
    }
}