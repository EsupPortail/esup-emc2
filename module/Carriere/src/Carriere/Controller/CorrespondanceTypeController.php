<?php

namespace Carriere\Controller;

use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Carriere\Service\CorrespondanceType\CorrespondanceTypeServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CorrespondanceTypeController extends AbstractActionController {
    use CorrespondanceServiceAwareTrait;
    use CorrespondanceTypeServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $types = $this->getCorrespondanceTypeService()->getCorrespondancesTypes();

        return new ViewModel([
            'types' => $types
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $type = $this->getCorrespondanceTypeService()->getRequestedCorrespondanceType($this);
        $correspondances = $this->getCorrespondanceService()->getCorrespondancesByType($type);

        return new ViewModel([
            'title' => "Affichage du type de correspondance [".$type->getCode()."]",
            'type' => $type,
            'correspondances' => $correspondances,
        ]);
    }
}