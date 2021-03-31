<?php

namespace Application\Controller;

use Application\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Application\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Formation\Service\FormationElement\FormationElementServiceAwareTrait;
use Zend\Http\Request;
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

    public function supprimerAction()
    {
        $elementType = $this->params()->fromRoute('type');
        $elementId = $this->params()->fromRoute('id');

        $element = null; $service = null;
        switch ($elementType) {
            case ElementController::TYPE_APPLICATION :
                $element = $this->getApplicationElementService()->getApplicationElement($elementId);
                $service = $this->getApplicationElementService();
                break;
            case ElementController::TYPE_COMPETENCE :
                $element = $this->getCompetenceElementService()->getCompetenceElement($elementId);
                $service = $this->getCompetenceElementService();
                break;
            case ElementController::TYPE_FORMATION :
                $element = $this->getFormationElementService()->getFormationElement($elementId);
                $service = $this->getFormationElementService();
                break;
        }

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $service->delete($element);
            exit();
        }

        $vm = new ViewModel();
        if ($element !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'élément " . $element->getObjet()->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('element/supprimer', ["type" => $elementType, "id" => $elementId], [], true),
            ]);
        }
        return $vm;
    }

}