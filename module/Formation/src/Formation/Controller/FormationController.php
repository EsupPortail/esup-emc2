<?php

namespace Formation\Controller;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\CompetenceElement;
use Element\Form\ApplicationElement\ApplicationElementFormAwareTrait;
use Element\Form\CompetenceElement\CompetenceElementFormAwareTrait;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Formation\Entity\Db\Formation;
use Formation\Form\Formation\FormationFormAwareTrait;
use Formation\Form\SelectionFormation\SelectionFormationFormAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationElement\FormationElementServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class FormationController extends AbstractActionController
{
    use FormationInstanceServiceAwareTrait;
    use FormationElementServiceAwareTrait;
    use FormationServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use ParcoursDeFormationServiceAwareTrait;
    use FormationFormAwareTrait;

    use ApplicationElementFormAwareTrait;
    use ApplicationElementServiceAwareTrait;
    use CompetenceElementFormAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use SelectionFormationFormAwareTrait;

    /** CRUD **********************************************************************************************************/

    public function indexAction() : ViewModel
    {
        $groupe = $this->params()->fromQuery('groupe');
        $groupe_ = ($groupe !== null AND $groupe !== "")?$this->getFormationGroupeService()->getFormationGroupe((int) $groupe):null;
        $source = $this->params()->fromQuery('source');
        $historise = $this->params()->fromQuery('historise');

        $formations = $this->getFormationService()->getFormationsByGroupe($groupe_);
        if ($source !== null AND $source !== "") $formations = array_filter($formations, function (Formation $a) use ($source) { return $a->getSource() === $source; });
        if ($historise !== null AND $historise !== "") $formations = array_filter($formations, function (Formation $a) use ($historise) {
            if ($historise === "1") return $a->estHistorise();
            if ($historise === "0") return $a->estNonHistorise();
            return true;
        });

        return new ViewModel([
            'formations' => $formations,
            'groupes' => $this->getFormationGroupeService()->getFormationsGroupesAsOption(),
            'groupe' => $groupe,
            'source' => $source,
            'historise' => $historise,
        ]);
    }

    public function ajouterAction() : ViewModel
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
                $formation->setSource(HasSourceInterface::SOURCE_EMC2);
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

    public function editerAction() : ViewModel
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

    public function historiserAction() : Response
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $this->getFormationService()->historise($formation);
        return $this->redirect()->toRoute('formation', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $this->getFormationService()->restore($formation);
        return $this->redirect()->toRoute('formation', [], [], true);
    }

    public function detruireAction() : ViewModel
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

    /** AUTRES MODIFICATIONS ******************************************************************************************/

    public function modifierFormationInformationsAction() : ViewModel
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

    public function ajouterApplicationElementAction() : ViewModel
    {
        $type = $this->params()->fromRoute('type');
        $hasApplicationElement = $this->getFormationService()->getRequestedFormation($this);

        if ($hasApplicationElement !== null) {
            $element = new ApplicationElement();

            $form = $this->getApplicationElementForm();
            $form->setAttribute('action', $this->url()->fromRoute('formation/ajouter-application-element', ['type' => $type, 'id' => $hasApplicationElement->getId()], [], true));
            $form->bind($element);
            $form->masquerClef();

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

    public function ajouterCompetenceElementAction() : ViewModel
    {
        $type = $this->params()->fromRoute('type');
        $hasCompetenceElement = $this->getFormationService()->getRequestedFormation($this);

        if ($hasCompetenceElement !== null) {
            $element = new CompetenceElement();

            $form = $this->getCompetenceElementForm();
            $form->setAttribute('action', $this->url()->fromRoute('formation/ajouter-competence-element', ['type' => $type, 'id' => $hasCompetenceElement->getId()], [], true));
            $form->bind($element);
            $form->masquerClef();

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

    /** ACTIONS DE RECHERCHE ******************************************************************************************/

    public function rechercherFormationAction() : JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $formations = $this->getFormationService()->findFormationByTerm($term);
            $result = $this->getFormationService()->formatFormationtJSON($formations);
            return new JsonModel($result);
        }
        exit;
    }

    /** DEBOULONNAGE **************************************************************************************************/

    public function dedoublonnerAction() : ViewModel
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $form = $this->getSelectionFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/dedoublonner', ['formation' => $formation->getId()], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $formationSub = $this->getFormationService()->getFormation($data['formations'][0]);

            if ($formationSub AND $formationSub !== $formation) {
                //décalages des éléments
                $elements = $this->getFormationElementService()->getElementsByFormation($formation);
                foreach ($elements as $element) {
                    $element->setFormation($formationSub);
                    $this->getFormationElementService()->update($element);
                }
                //décalage des instances
                $instances = $this->getFormationInstanceService()->getFormationsInstancesByFormation($formation);
                foreach ($instances as $instance) {
                    $instance->setFormation($formationSub);
                    $this->getFormationInstanceService()->update($instance);
                }

                //decalage des applications acquises
                $applications = $formation->getApplicationCollection();
                foreach ($applications as $application) {
                    if ($formationSub->hasApplication($application->getApplication())) {
                        $this->getApplicationElementService()->delete($application);
                    } else {
                        $formation->removeApplicationElement($application);
                        $formationSub->addApplicationElement($application);
                    }
                }
                //decalage des compétences acquises
                $competences = $formation->getCompetenceCollection();
                foreach ($competences as $competence) {
                    if ($formationSub->hasCompetence($competence->getCompetence())) {
                        $this->getCompetenceElementService()->delete($competence);
                    } else {
                        $formation->removeCompetenceElement($competence);
                        $formationSub->addCompetenceElement($competence);
                    }
                }
                //effacement final
                $this->getFormationService()->delete($formation);
                $this->getFormationService()->update($formationSub);
            }

        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Sélection de la formation qui remplacera [".$formation->getLibelle()."]",
            'form' => $form,
        ]);
        return $vm;
    }
}