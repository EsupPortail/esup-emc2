<?php

namespace Application\Controller;

use Application\Entity\Db\Activite;
use Application\Entity\Db\FicheMetier;
use Application\Form\Activite\ActiviteForm;
use Application\Form\Activite\ActiviteFormAwareTrait;
use Application\Form\FicheMetier\ActiviteExistanteForm;
use Application\Form\FicheMetier\ActiviteExistanteFormAwareTrait;
use Application\Form\FicheMetier\ApplicationsForm;
use Application\Form\FicheMetier\ApplicationsFormAwareTrait;
use Application\Form\FicheMetier\FormationBaseForm;
use Application\Form\FicheMetier\FormationBaseFormAwareTrait;
use Application\Form\FicheMetier\FormationComportementaleForm;
use Application\Form\FicheMetier\FormationComportementaleFormAwareTrait;
use Application\Form\FicheMetier\FormationOperationnelleForm;
use Application\Form\FicheMetier\FormationOperationnelleFormAwareTrait;
use Application\Form\FicheMetier\FormationsForm;
use Application\Form\FicheMetier\FormationsFormAwareTrait;
use Application\Form\FicheMetier\GererCompetenceFormAwareTrait;
use Application\Form\FicheMetier\LibelleForm;
use Application\Form\FicheMetier\LibelleFormAwareTrait;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\Domaine\DomaineServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Form\Element\Select;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FicheMetierController extends  AbstractActionController{
    /** Traits associé aux services */
    use ActiviteServiceAwareTrait;
    use DomaineServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use RessourceRhServiceAwareTrait;
    /** Traits associé aux formulaires */
    use ActiviteFormAwareTrait;
    use ActiviteExistanteFormAwareTrait;
    use ApplicationsFormAwareTrait;
    use FormationsFormAwareTrait;
    use LibelleFormAwareTrait;
    use GererCompetenceFormAwareTrait;

    use ConfigurationServiceAwareTrait;

    use FormationBaseFormAwareTrait;
    use FormationComportementaleFormAwareTrait;
    use FormationOperationnelleFormAwareTrait;


    public function indexAction()
    {
        $domaineId = $this->params()->fromQuery('domaine');
        $domaines = $this->getDomaineService()->getDomaines();

        if ($domaineId === null) {
            $domaine = null;
            $fichesMetiers = $this->getFicheMetierService()->getFichesMetiers();
        } else {
            $domaine  = $this->getDomaineService()->getDomaine($domaineId);
            $fichesMetiers = $this->getFicheMetierService()->getFicheByDomaine($domaine);
        }

        return new ViewModel([
            'domaineSelect'  => $domaine,
            'domaines' => $domaines,
            'fiches'   => $fichesMetiers,
        ]);
    }

    public function afficherAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);

        return new ViewModel([
            'title' => 'Visualisation d\'une fiche métier',
            'fiche' => $fiche,
        ]);
    }

    public function editerAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', false);
        if ($fiche === null) $fiche = $this->getFicheMetierService()->getLastFicheMetier();

        return new ViewModel([
            'fiche' => $fiche,
        ]);
    }

    public function historiserAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);
        $this->getFicheMetierService()->historise($fiche);

        return $this->redirect()->toRoute('fiche-metier-type', [], [], true);
    }

    public function restaurerAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);
        $this->getFicheMetierService()->restore($fiche);

        return $this->redirect()->toRoute('fiche-metier-type', [], [], true);
    }

    public function detruireAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);
        $this->getFicheMetierService()->delete($fiche);

        return $this->redirect()->toRoute('fiche-metier-type', [], [], true);
    }

    public function ajouterAction()
    {
        /** @var FicheMetier $fiche */
        $fiche = new FicheMetier();

        /** @var LibelleForm $form */
        $form = $this->getLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/ajouter', [], [] , true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->create($fiche);

                $this->getConfigurationService()->addDefaultToFicheMetier($fiche);
                $this->getFicheMetierService()->update($fiche);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajout d\'une fiche metier',
            'form' => $form,
        ]);
        return $vm;

    }

    public function editerLibelleAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);

        /** @var LibelleForm $form */
        $form = $this->getLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/editer-libelle',['id' => $fiche->getId()],[], true));
        $form->bind($fiche);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fiche);
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

    public function ajouterNouvelleActiviteAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);

        $activite = new Activite();
        /** @var ActiviteForm $form */
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
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);

        /** @var ActiviteExistanteForm $form */
        $form = $this->getActiviteExistanteForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/ajouter-activite-existante', ['id' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /** @var Select $select */
        $select = $form->get('activite');
        $select->setValueOptions($this->getActiviteService()->getActivitesAsOptions($fiche));

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            $activite = $this->getActiviteService()->getActivite($data['activite']);
            $this->getActiviteService()->createFicheMetierTypeActivite($fiche, $activite);
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

        return $this->redirect()->toRoute('fiche-metier-type/editer', ['id' => $couple->getFiche()->getId()], [], true);
    }

    public function deplacerActiviteAction()
    {
        $direction = $this->params()->fromRoute('direction');
        $coupleId = $this->params()->fromRoute('id');
        $couple = $this->getActiviteService()->getFicheMetierTypeActivite($coupleId);

        if ($direction === 'up')    $this->getActiviteService()->moveUp($couple);
        if ($direction === 'down')  $this->getActiviteService()->moveDown($couple);

        $this->getActiviteService()->updateFicheMetierTypeActivite($couple);

        return $this->redirect()->toRoute('fiche-metier-type/editer', ['id' => $couple->getFiche()->getId()], [], true);
    }

    public function modifierApplicationAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id');

        /** @var ApplicationsForm $form */
        $form = $this->getApplicationsForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/modifier-application', ['id' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            //Pourquoi on ne passe pas dans l'hydration quand $data['applications'] est null (bizarrerie du multiple et du bootstrapselet ???);
            if($data['applications'] === null) $data['applications'] = [];
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fiche);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modification des applications',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierFormationAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id');

        /** @var FormationsForm $form */
        $form = $this->getFormationsForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/modifier-formation', ['id' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            //Pourquoi on ne passe pas dans l'hydration quand $data['applications'] est null (bizarrerie du multiple et du bootstrapselet ???);
            if($data['formations'] === null) $data['formations'] = [];
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fiche);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modification des formations',
            'form' => $form,
        ]);
        return $vm;
    }

    public function gererCompetencesAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this);
        $form = $this->getGererCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/gerer-competences',['fiche' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /**  @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            //Pourquoi on ne passe pas dans l'hydration quand $data['applications'] est null (bizarrerie du multiple et du bootstrapselet ???);
            if($data['competences'] === null) $data['competences'] = [];
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fiche);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Gestion des compétences de la fiche métier ". $fiche->getMetier()->getLibelle(),
            'form'  => $form,
        ]);
        return $vm;
    }
}