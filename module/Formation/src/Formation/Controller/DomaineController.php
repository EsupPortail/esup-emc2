<?php

namespace Formation\Controller;

use Formation\Entity\Db\Domaine;
use Formation\Form\Domaine\DomaineFormAwareTrait;
use Formation\Form\SelectionFormation\SelectionFormationFormAwareTrait;
use Formation\Service\Domaine\DomaineServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class DomaineController extends AbstractActionController {
    use DomaineServiceAwareTrait;
    use DomaineFormAwareTrait;
    use SelectionFormationFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $domaines = $this->getDomaineService()->getDomaines('ordre', 'ASC', true);

        return new ViewModel([
            'domaines' => $domaines,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);

        return new ViewModel([
            'title' => "Affichage de du domaine [".$domaine->getLibelle()."]",
            'domaine' => $domaine,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $domaine = new Domaine();

        $form = $this->getDomaineForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-domaine/ajouter', [], [], true));
        $form->bind($domaine);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getDomaineService()->create($domaine);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un domaine de formation",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);

        $form = $this->getDomaineForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-domaine/modifier', ['domaine' => $domaine->getId()], [], true));
        $form->bind($domaine);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getDomaineService()->update($domaine);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification du domaine [".$domaine->getLibelle()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction() : Response
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);
        $this->getDomaineService()->historise($domaine);

        $retour = $this->params()->fromRoute('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation-domaine', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);
        $this->getDomaineService()->restore($domaine);

        $retour = $this->params()->fromRoute('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation-domaine', [], [], true);
    }

    public function supprimerAction() : ViewModel
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getDomaineService()->delete($domaine);
            exit();
        }

        $vm = new ViewModel();
        if ($domaine !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du domaine " . $domaine->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-domaine/supprimer', ["domaine" => $domaine->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function gererFormationsAction(): ViewModel
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);
        $form = $this->getSelectionFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-domaine/gerer-formations', ['domaine' => $domaine->getId()], [], true));
        $form->bind($domaine);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {

                /** note  : les domaines ne prote pas vraiment les formations ... */
                $this->getDomaineService()->update($domaine);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Gérer les formations associé aux domaines [".$domaine->getLibelle()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation/selection-formation-form');
        return $vm;
    }

}