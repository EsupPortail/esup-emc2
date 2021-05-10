<?php

namespace Formation\Controller;

use Application\Entity\Db\ApplicationElement;
use Application\Entity\Db\CompetenceElement;
use Application\Form\ApplicationElement\ApplicationElementFormAwareTrait;
use Application\Form\CompetenceElement\CompetenceElementFormAwareTrait;
use Application\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Application\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Formation\Entity\Db\Formation;
use Formation\Form\Formation\FormationFormAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FormationController extends AbstractActionController
{
    use EtatServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use FormationServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use ParcoursDeFormationServiceAwareTrait;
    use FormationFormAwareTrait;

    use ApplicationElementFormAwareTrait;
    use ApplicationElementServiceAwareTrait;
    use CompetenceElementFormAwareTrait;
    use CompetenceElementServiceAwareTrait;

    public function indexAction()
    {
        $formations = $this->getFormationService()->getFormations();
        $groupes = $this->getFormationGroupeService()->getFormationsGroupes('libelle');

        return new ViewModel([
            'formations' => $formations,
            'groupes' => $groupes,
            'etatService' => $this->getEtatService(),
        ]);
    }

    public function ajouterAction()
    {
        $formation = new Formation();
        $form = $this->getFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/ajouter', [], [], true));
        $form->bind($formation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationService()->create($formation);
                $formation->setSource(Formation::SOURCE_EMC2);
                $formation->setIdSource($formation->getId());
                $this->getFormationService()->update($formation);
                exit;
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une formation',
            'form' => $form,
        ]);
        return $vm;
    }

    public function editerAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $form = $this->getFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/editer', ['formation' => $formation->getId()], [], true));
        $form->bind($formation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationService()->update($formation);
            }
        }

        $instances = $this->getFormationInstanceService()->getFormationsInstancesByFormation($formation);

        $vm = new ViewModel();
        $vm->setTemplate('formation/formation/modifier');
        $vm->setVariables([
            'title' => 'Edition d\'une formation',
            'formation' => $formation,
            'instances' => $instances,
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $this->getFormationService()->historise($formation);
        return $this->redirect()->toRoute('formation', [], ['fragment' => 'formation'], true);
    }

    public function restaurerAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $this->getFormationService()->restore($formation);
        return $this->redirect()->toRoute('formation', [], ['fragment' => 'formation'], true);
    }

    public function detruireAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationService()->delete($formation);
            exit();
        }

        $vm = new ViewModel();
        if ($formation !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la formation [" . $formation->getLibelle() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation/detruire', ["formation" => $formation->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function modifierFormationInformationsAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $form = $this->getFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/modifier-formation-informations', ['formation' => $formation->getId()], [], true));
        $form->bind($formation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationService()->update($formation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier les informations de la formation',
            'formation' => $formation,
            'form' => $form,
        ]);
        return $vm;
    }

    /** APPLICATION */

    public function ajouterApplicationElementAction()
    {
        $type = $this->params()->fromRoute('type');
        $hasApplicationElement = $this->getFormationService()->getRequestedFormation($this);
        $clef='masquer';

        if ($hasApplicationElement !== null) {
            $element = new ApplicationElement();

            $form = $this->getApplicationElementForm();
            $form->setAttribute('action', $this->url()->fromRoute('formation/ajouter-application-element', ['type' => $type, 'id' => $hasApplicationElement->getId()], [], true));
            $form->bind($element);
            if ($clef === 'masquer') $form->masquerClef();

            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                $form->setData($data);
                if ($form->isValid()) {
                    $this->getApplicationElementService()->create($element);
                    $hasApplicationElement->addApplicationElement($element);
                    $this->getFormationService()->update($hasApplicationElement);
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

    public function ajouterCompetenceElementAction()
    {
        $type = $this->params()->fromRoute('type');
        $hasCompetenceElement = $this->getFormationService()->getRequestedFormation($this);
        $clef='masquer';

        if ($hasCompetenceElement !== null) {
            $element = new CompetenceElement();

            $form = $this->getCompetenceElementForm();
            $form->setAttribute('action', $this->url()->fromRoute('formation/ajouter-competence-element', ['type' => $type, 'id' => $hasCompetenceElement->getId()], [], true));
            $form->bind($element);
            if ($clef === 'masquer') $form->masquerClef();

            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                $form->setData($data);
                if ($form->isValid()) {
                    $this->getCompetenceElementService()->create($element);
                    $hasCompetenceElement->addCompetenceElement($element);
                    $this->getFormationService()->update($hasCompetenceElement);
                }
            }

            $vm = new ViewModel([
                'title' => "Ajout d'une competence",
                'form' => $form,
            ]);
            $vm->setTemplate('application/default/default-form');
            return $vm;
        }
        exit();
    }
}