<?php

namespace Octopus\Controller;

use Octopus\Service\Immobilier\ImmobilierService;
use Octopus\Service\Structure\StructureService;
use Zend\Mvc\Controller\ControllerManager;

class OctopusControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var StructureService $structureService
         * @var ImmobilierService $immobilierService
         */
        $structureService = $manager->getServiceLocator()->get(StructureService::class);
        $immobilierService = $manager->getServiceLocator()->get(ImmobilierService::class);

        /** @var OctopusController $controller */
        $controller = new OctopusController();
        $controller->setStructureService($structureService);
        $controller->setImmobiliserService($immobilierService);
        return $controller;
    }
}