<?php

namespace Application\Controller\FicheMetier;

use Application\Entity\Db\Activite;
use Application\Entity\Db\FicheMetierType;
use Application\Form\Activite\ActiviteFormAwareTrait;
use Application\Form\FicheMetierType\ActiviteExistanteForm;
use Application\Form\FicheMetierType\ActiviteExistanteFormAwareTrait;
use Application\Form\FicheMetierType\ApplicationsForm;
use Application\Form\FicheMetierType\ApplicationsFormAwareTrait;
use Application\Form\FicheMetierType\FormationBaseForm;
use Application\Form\FicheMetierType\FormationBaseFormAwareTrait;
use Application\Form\FicheMetierType\FormationComportementaleForm;
use Application\Form\FicheMetierType\FormationComportementaleFormAwareTrait;
use Application\Form\FicheMetierType\FormationOperationnelleForm;
use Application\Form\FicheMetierType\FormationOperationnelleFormAwareTrait;
use Application\Form\FicheMetierType\LibelleForm;
use Application\Form\FicheMetierType\LibelleFormAwareTrait;
use Application\Form\FicheMetierType\MissionsPrincipalesForm;
use Application\Form\FicheMetierType\MissionsPrincipalesFormAwareTrait;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FicheMetierTypeController extends  AbstractActionController{
    /** Traits associé aux services */
    use ActiviteServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    /** Traits associé aux formulaires */
    use ActiviteFormAwareTrait;
    use ActiviteExistanteFormAwareTrait;
    use ApplicationsFormAwareTrait;
    use FormationBaseFormAwareTrait;
    use FormationComportementaleFormAwareTrait;
    use FormationOperationnelleFormAwareTrait;
    use LibelleFormAwareTrait;
    use MissionsPrincipalesFormAwareTrait;

    public function indexAction()
    {
        $fichesMetiersTypes = $this->getFicheMetierService()->getFichesMetiersTypes();

        return new ViewModel([
            'fiches' => $fichesMetiersTypes,
        ]);
    }

    public function afficherAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetierType($this, 'id', true);
        $activites = $this->getActiviteService()->getActivitesByFicheMetierType($fiche);

        return new ViewModel([
            'fiche' => $fiche,
            'activites' => $activites,
        ]);
    }

    public function ajouterAction()
    {
        /** @var FicheMetierType $fiche */
        $fiche = new FicheMetierType();

        /** @var LibelleForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(LibelleForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/ajouter', [], [] , true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->createFicheMetierType($fiche);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajout d\'une fiche metier type',
            'form' => $form,
        ]);
        return $vm;

    }

    public function editerLibelleAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetierType($this, 'id', true);

        /** @var MissionsPrincipalesForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(LibelleForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/editer-libelle',['id' => $fiche->getId()],[], true));
        $form->bind($fiche);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getFicheMetierService()->updateFicheMetierType($fiche);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier le libellé',
            'form' => $form,
        ]);
        return $vm;
    }

    public function editerMissionsPrincipalesAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetierType($this, 'id', true);

        /** @var MissionsPrincipalesForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(MissionsPrincipalesForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/editer-missions-principales',['id' => $fiche->getId()],[], true));
        $form->bind($fiche);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getFicheMetierService()->updateFicheMetierType($fiche);
                //$this->redirect()->toRoute('fiche-metier-type/afficher', ['id' => $fiche->getId()], [] , true);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Éditer missions principales',
            'form' => $form,
        ]);
        return $vm;
    }

    public function ajouterNouvelleActiviteAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetierType($this, 'id', true);

        $activite = new Activite();

        /** @var MissionsPrincipalesForm $form */
        $form = $this->getActiviteForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/ajouter-nouvelle-activite',['id' => $fiche->getId()],[], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getActiviteService()->create($activite);

                $this->getActiviteService()->createFicheMetierTypeActivite($fiche, $activite);
                //$this->redirect()->toRoute('fiche-metier-type/afficher', ['id' => $fiche->getId()], [], true);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une nouvelle activité',
            'form' => $form,
        ]);
        return $vm;

    }

    public function ajouterActiviteExistanteAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetierType($this, 'id', true);

        /** @var ActiviteExistanteForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(ActiviteExistanteForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/ajouter-activite-existante', ['id' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

//            if ($form->isValid()) {
                $activite = $this->getActiviteService()->getActivite($data['activite']);
                $this->getActiviteService()->createFicheMetierTypeActivite($fiche, $activite);
//            }
        }

        $activites = $this->getActiviteService()->getActivites();
        $options = [];
        foreach($activites as $activite) {
            $options[$activite->getId()] = [
                "title" => $activite->getLibelle(),
                "description" => $activite->getDescription(),
            ];
        }

        return new ViewModel([
            'title' => 'Ajouter une activité existante',
            'form' => $form,
            'options' => $options,
        ]);
    }

    public function retirerActiviteAction()
    {
        $coupleId = $this->params()->fromRoute('id');
        $couple = $this->getActiviteService()->getFicheMetierTypeActivite($coupleId);

        $this->getActiviteService()->removeFicheMetierTypeActivite($couple);

        $this->redirect()->toRoute('fiche-metier-type/afficher', ['id' => $couple->getFiche()->getId()], [], true);
    }

    public function deplacerActiviteAction()
    {
        $direction = $this->params()->fromRoute('direction');
        $coupleId = $this->params()->fromRoute('id');
        $couple = $this->getActiviteService()->getFicheMetierTypeActivite($coupleId);

        if ($direction === 'up')    $this->getActiviteService()->moveUp($couple);
        if ($direction === 'down')  $this->getActiviteService()->moveDown($couple);

        $this->getActiviteService()->updateFicheMetierTypeActivite($couple);

        $this->redirect()->toRoute('fiche-metier-type/afficher', ['id' => $couple->getFiche()->getId()], [], true);
    }

    public function modifierConnaissancesAction() {

        $ficheId = $this->params()->fromRoute('id');
        $fiche = $this->getFicheMetierService()->getFicheMetierType($ficheId);

        /** @var FormationBaseForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(FormationBaseForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/modifier-connaissances', ['id' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->updateFicheMetierType($fiche);
//                $this->redirect()->toRoute('fiche-metier-type/afficher', ['id' => $fiche->getId()]);
            }
        }

        return new ViewModel([
           'type' => 'connaissances',
            'title' => 'Modification des connaissances',
           'form' => $form,
        ]);
    }

    public function modifierOperationnelleAction() {

        $ficheId = $this->params()->fromRoute('id');
        $fiche = $this->getFicheMetierService()->getFicheMetierType($ficheId);

        /** @var FormationOperationnelleForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(FormationOperationnelleForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/modifier-operationnelle', ['id' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->updateFicheMetierType($fiche);
//                $this->redirect()->toRoute('fiche-metier-type/afficher', ['id' => $fiche->getId()]);
            }
        }

        return new ViewModel([
            'type' => 'operationnelle',
            'title' => 'Modification des compétences opérationnelles',
            'form' => $form,
        ]);
    }

    public function modifierComportementaleAction() {

        $ficheId = $this->params()->fromRoute('id');
        $fiche = $this->getFicheMetierService()->getFicheMetierType($ficheId);

        /** @var FormationComportementaleForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(FormationComportementaleForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/modifier-comportementale', ['id' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->updateFicheMetierType($fiche);
//                $this->redirect()->toRoute('fiche-metier-type/afficher', ['id' => $fiche->getId()]);
            }
        }

        return new ViewModel([
            'type' => 'comportementale',
            'title' => 'Modification des compétences comportementales',
            'form' => $form,
        ]);
    }

    public function modifierApplicationAction() {

        $ficheId = $this->params()->fromRoute('id');
        $fiche = $this->getFicheMetierService()->getFicheMetierType($ficheId);

        /** @var ApplicationsForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(ApplicationsForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/modifier-application', ['id' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->updateFicheMetierType($fiche);
//                $this->redirect()->toRoute('fiche-metier-type/afficher', ['id' => $fiche->getId()]);
            }
        }

        return new ViewModel([
            'type' => 'comportementale',
            'title' => 'Modification des compétences comportementales',
            'form' => $form,
        ]);
    }
}