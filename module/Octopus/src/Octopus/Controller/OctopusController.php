<?php

namespace Octopus\Controller;

use Octopus\Service\Immobilier\ImmobilierServiceAwareTrait;
use Octopus\Service\Structure\StructureServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class OctopusController extends AbstractActionController {
    use ImmobilierServiceAwareTrait;
    use StructureServiceAwareTrait;

    public function indexAction() {

        $structureTypes = $this->getStructureService()->getStructuresTypes('libelle');
        $structures     = $this->getStructureService()->getStructures('libelleCourt');

        $locals         = $this->getImmobiliserService()->getImmobilierLocals('libelle');
        $niveaux        = $this->getImmobiliserService()->getImmobilierNiveaux('libelle');
        $batiments      = $this->getImmobiliserService()->getImmobilierBatiments('libelle');
        $sites          = $this->getImmobiliserService()->getImmobilierSites('libelle');


        return new ViewModel([
            'structureTypes' => $structureTypes,
            'structures' => $structures,

            'locals' => $locals,
            'niveaux' => $niveaux,
            'batiments' => $batiments,
            'sites' => $sites,
        ]);
    }
}