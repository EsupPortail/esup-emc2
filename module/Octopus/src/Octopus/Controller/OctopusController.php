<?php

namespace Octopus\Controller;

use Octopus\Service\Structure\StructureServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class OctopusController extends AbstractActionController {
    use StructureServiceAwareTrait;

    public function indexAction() {

        $structureTypes = $this->getStructureService()->getStructuresTypes('libelle');
        $structures     = $this->getStructureService()->getStructures('libelleCourt');

        return new ViewModel([
            'structureTypes' => $structureTypes,
            'structures' => $structures,
        ]);
    }
}