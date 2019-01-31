<?php

namespace Octopus\Controller;

use Octopus\Service\Structure\StructureService;
use Zend\Mvc\Controller\ControllerManager;

class OctopusControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /** @var StructureService $structureService */
        $structureService = $manager->getServiceLocator()->get(StructureService::class);

        /** @var OctopusController $controller */
        $controller = new OctopusController();
        $controller->setStructureService($structureService);
        return $controller;
    }
}