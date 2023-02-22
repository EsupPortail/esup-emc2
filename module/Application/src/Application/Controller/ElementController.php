<?php

namespace Application\Controller;

use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\CompetenceElement;
use Element\Form\ApplicationElement\ApplicationElementFormAwareTrait;
use Element\Form\CompetenceElement\CompetenceElementFormAwareTrait;
use Element\Form\SelectionNiveau\SelectionNiveauFormAwareTrait;
use Element\Service\Application\ApplicationServiceAwareTrait;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\Niveau\NiveauServiceAwareTrait;
use FicheMetier\Entity\Db\FicheMetier;
use Formation\Service\FormationElement\FormationElementServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ElementController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use ApplicationServiceAwareTrait;
    use ApplicationElementServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FormationElementServiceAwareTrait;
    use NiveauServiceAwareTrait;

    use ApplicationElementFormAwareTrait;
    use CompetenceElementFormAwareTrait;
    use SelectionNiveauFormAwareTrait;

    const TYPE_APPLICATION = 'Application';
    const TYPE_COMPETENCE = 'Competence';
    const TYPE_FORMATION = 'Formation';

    public function afficherAction(): ViewModel
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
            'title' => "Affichage d'un élément de type " . $elementType,
            'type' => $elementType,
            'id' => $elementId,
            'element' => $element,
        ]);
    }

    public function supprimerAction(): ViewModel
    {
        $elementType = $this->params()->fromRoute('type');
        $elementId = $this->params()->fromRoute('id');

        $element = null;
        $service = null;
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
                'action' => $this->url()->fromRoute('element_/supprimer', ["type" => $elementType, "id" => $elementId], [], true),
            ]);
        }
        return $vm;
    }

    public function changerNiveauAction(): ViewModel
    {
        $elementType = $this->params()->fromRoute('type');
        $elementId = $this->params()->fromRoute('id');
        $clef = $this->params()->fromRoute('clef');

        $element = null;
        $service = null;
        switch ($elementType) {
            case ElementController::TYPE_APPLICATION :
                $element = $this->getApplicationElementService()->getApplicationElement($elementId);
                $service = $this->getApplicationElementService();
                break;
            case ElementController::TYPE_COMPETENCE :
                $element = $this->getCompetenceElementService()->getCompetenceElement($elementId);
                $service = $this->getCompetenceElementService();
                break;
        }

        $form = $this->getSelectionNiveauForm();
        $form->setType($elementType);
        $form->init();
        $form->setAttribute('action', $this->url()->fromRoute('element_/changer-niveau', ['type' => $elementType, 'id' => $element->getId(), 'clef' => $clef], [], true));
        $form->bind($element);
        if ($clef === 'masquer') $form->masquerClef();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $service->update($element);
            }
        }

        $vm = new ViewModel([
            'title' => "Changer le niveau de maîtrise",
            'form' => $form,
        ]);
        $vm->setTemplate('application/default/default-form');
        return $vm;
    }

    public function ajouterApplicationElementAction(): ViewModel
    {
        $type = $this->params()->fromRoute('type');
        $multiple = $this->params()->fromQuery('multiple');

        $hasApplicationElement = null;
        switch ($type) {
            case Agent::class :
                $hasApplicationElement = $this->getAgentService()->getRequestedAgent($this, 'id');
                break;
            case FicheMetier::class :
                $hasApplicationElement = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id');
                break;
        }
        $clef = $this->params()->fromRoute('clef');

        $application = null;
        if ($applicationId = $this->params()->fromQuery('application')) {
            $application = $this->getApplicationService()->getApplication($applicationId);
        }

        if ($hasApplicationElement !== null) {
            $form = $this->getApplicationElementForm();

            if ($multiple === '1') {
                $form->get('application')->setAttribute('multiple', 'multiple');
                $form->remove('clef');
                $form->remove('niveau');
            }

            $element = new ApplicationElement();
            if ($application !== null) {
                $element->setApplication($application);
            }
            if ($clef === 'masquer') $form->masquerClef();

            $form->setAttribute('action', $this->url()->fromRoute('element_/ajouter-application-element',
                ['type' => $type, 'id' => $hasApplicationElement->getId()],
                ['query' => ['multiple' => $multiple]], true));
            $form->bind($element);


            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                $form->setData($data);
                if ($multiple !== '1') {
                    if ($form->isValid()) {
                        $this->getApplicationElementService()->create($element);
                        $hasApplicationElement->addApplicationElement($element);
                        switch ($type) {
                            case Agent::class :
                                $this->getAgentService()->update($hasApplicationElement);
                                break;
                            case FicheMetier::class :
                                $this->getFicheMetierService()->update($hasApplicationElement);
                                break;
                        }
                    }
                } else {
                    $niveau = $this->getNiveauService()->getMaitriseNiveau($data['niveau']);
                    $clef = (isset($data['clef']) and $data['clef'] === "1");
                    foreach ($data['application'] as $applicationId) {
                        $application = $this->getApplicationService()->getApplication($applicationId);
                        if ($application !== null and !$hasApplicationElement->hasApplication($application)) {
                            $element = new ApplicationElement();
                            $element->setClef($clef);
                            $element->setApplication($application);
                            $element->setNiveauMaitrise($niveau);
                            $element->setClef($clef);
                            $hasApplicationElement->addApplicationElement($element);
                            $this->getApplicationElementService()->create($element);
                        }
                    }
                    switch ($type) {
                        case Agent::class :
                            $this->getAgentService()->update($hasApplicationElement);
                            break;
                        case FicheMetier::class :
                            $this->getFicheMetierService()->update($hasApplicationElement);
                            break;
                    }
                }
            }

            $vm = new ViewModel([
                'title' => "Ajout d'une application",
                'form' => $form,
            ]);
            $vm->setTemplate('application/default/default-form');
            return $vm;
        }
        exit();
    }

    public function ajouterCompetenceElementAction(): ViewModel
    {
        $type = $this->params()->fromRoute('type');
        $multiple = $this->params()->fromQuery('multiple');

        $hasCompetenceElement = null;
        switch ($type) {
            case Agent::class :
                $hasCompetenceElement = $this->getAgentService()->getRequestedAgent($this, 'id');
                break;
            case FicheMetier::class :
                $hasCompetenceElement = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id');
                break;
        }
        $clef = $this->params()->fromRoute('clef');

        $competence = null;
        if ($competenceId = $this->params()->fromQuery('competence')) {
            $competence = $this->getCompetenceService()->getCompetence($competenceId);
        }

        if ($hasCompetenceElement !== null) {
            $form = $this->getCompetenceElementForm();

            if ($multiple === '1') {
                $form->get('competence')->setAttribute('multiple', 'multiple');
                $form->remove('clef');
                $form->remove('niveau');
            }

            $element = new CompetenceElement();
            if ($competence !== null) {
                $element->setCompetence($competence);
            }
            if ($clef === 'masquer') $form->masquerClef();

            $form->setAttribute('action', $this->url()->fromRoute('element_/ajouter-competence-element',
                ['type' => $type, 'id' => $hasCompetenceElement->getId()],
                ['query' => ['multiple' => $multiple]], true));
            $form->bind($element);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                $form->setData($data);
                if ($multiple !== '1') {
                    if ($form->isValid()) {
                        $this->getCompetenceElementService()->create($element);
                        $hasCompetenceElement->addCompetenceElement($element);
                        switch ($type) {
                            case Agent::class :
                                $this->getAgentService()->update($hasCompetenceElement);
                                break;
                            case FicheMetier::class :
                                $this->getFicheMetierService()->update($hasCompetenceElement);
                                break;
                        }
                    }
                } else {
                    $niveau = $this->getNiveauService()->getMaitriseNiveau($data['niveau']);
                    $clef = (isset($data['clef']) and $data['clef'] === "1");
                    foreach ($data['competence'] as $competenceId) {
                        $competence = $this->getCompetenceService()->getCompetence($competenceId);
                        if ($competence !== null and !$hasCompetenceElement->hasCompetence($competence)) {
                            $element = new CompetenceElement();
                            $element->setClef($clef);
                            $element->setCompetence($competence);
                            $element->setNiveauMaitrise($niveau);
                            $element->setClef($clef);
                            $hasCompetenceElement->addCompetenceElement($element);
                            $this->getCompetenceElementService()->create($element);
                        }
                    }
                    switch ($type) {
                        case Agent::class :
                            $this->getAgentService()->update($hasCompetenceElement);
                            break;
                        case FicheMetier::class :
                            $this->getFicheMetierService()->update($hasCompetenceElement);
                            break;
                    }
                }
            }

            $vm = new ViewModel([
                'title' => "Ajout d'une compétence",
                'form' => $form,
            ]);
            $vm->setTemplate('application/default/default-form');
            return $vm;
        }
        exit();
    }
}