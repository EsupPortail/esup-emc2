<?php

namespace Application\Controller;

use Application\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Application\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Formation\Service\FormationElement\FormationElementServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ElementController extends AbstractActionController {
    use ApplicationElementServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use FormationElementServiceAwareTrait;

    const TYPE_APPLICATION = 'Application';
    const TYPE_COMPETENCE = 'Competence';
    const TYPE_FORMATION = 'Formation';

    public function afficherAction()
    {
        $elementType = $this->params()->fromRoute('type');
        $elementId = $this->params()->fromRoute('id');

        $element = null;
        switch ($elementType) {
            case ElementController::TYPE_APPLICATION :
                $element = $this->getApplicationElementService()->getApplicationElement($elementId);
                break;
            case ElementController::TYPE_COMPETENCE :
                $element = $this->getCompetenceElementService()->getCompetenceElement($elementId);
                break;
            case ElementController::TYPE_FORMATION :
                $element = $this->getFormationElementService()->getFormationElement($elementId);
                break;
        }

        return new ViewModel([
            'title' => "Affichage d'un élément de type ".$elementType,
            'type' => $elementType,
            'id' => $elementId,
            'element' => $element,
        ]);
    }

}