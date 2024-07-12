<?php

namespace Formation\Controller;

use Formation\Entity\Db\Lieu;
use Formation\Form\Lieu\LieuFormAwareTrait;
use Formation\Service\Lieu\LieuServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

class LieuController extends AbstractActionController {
    use LieuServiceAwareTrait;
    use LieuFormAwareTrait;

    /** CRUD **********************************************************************************************************/

    public function indexAction(): ViewModel
    {
        $lieux = $this->getLieuService()->getLieux(true);

        return new ViewModel([
            'lieux' => $lieux
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $lieu = $this->getLieuService()->getRequestedLieu($this);

        return new ViewModel([
            'title' => "Affichage du lieu",
            'lieu' => $lieu
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $lieu = new Lieu();
        $form = $this->getLieuForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/lieu/ajouter', [], [], true));
        $form->bind($lieu);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getLieuService()->create($lieu);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un lieu",
            'form' => $form
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $lieu = $this->getLieuService()->getRequestedLieu($this);
        $form = $this->getLieuForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/lieu/modifier', ['lieu' => $lieu->getId()], [], true));
        $form->bind($lieu);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getLieuService()->update($lieu);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'un lieu",
            'form' => $form
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $lieu = $this->getLieuService()->getRequestedLieu($this);
        $this->getLieuService()->historise($lieu);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation/lieu', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $lieu = $this->getLieuService()->getRequestedLieu($this);
        $this->getLieuService()->restore($lieu);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation/lieu', [], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $lieu = $this->getLieuService()->getRequestedLieu($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getLieuService()->delete($lieu);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($lieu !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du lieu",
                'text' => "La suppression est définitive. Êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation/lieu/supprimer', ["lieu" => $lieu->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** RECHERCHE *****************************************************************************************************/

    public function rechercherAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $stagiaires = $this->getLieuService()->getLieuxbyTerm($term);
            $result = $this->getLieuService()->formatLieuJSON($stagiaires);
            return new JsonModel($result);
        }
        exit;
    }
}