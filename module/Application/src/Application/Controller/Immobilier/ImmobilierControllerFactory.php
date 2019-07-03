<?php

namespace Application\Controller\Immobilier;

use Application\Service\Immobilier\ImmobilierService;
use Zend\Mvc\Controller\ControllerManager;

class ImmobilierControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var ImmobilierService $immobilierService
         */
        $immobilierService = $manager->getServiceLocator()->get(ImmobilierService::class);

        /** @var ImmobilierController $controller */
        $controller = new ImmobilierController();
        $controller->setImmobilierService($immobilierService);
        return $controller;
    }

}