<?php

namespace EmploiRepere\Controller;

use EmploiRepere\Entity\Db\EmploiRepere;
use EmploiRepere\Form\EmploiRepere\EmploiRepereFormAwareTrait;
use EmploiRepere\Service\EmploiRepere\EmploiRepereServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

class EmploiRepereController extends  AbstractActionController {
    use EmploiRepereServiceAwareTrait;
    use EmploiRepereFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $emploisReperes = $this->getEmploiRepereService()->getEmploisReperes(true);

        return new ViewModel([
            'emploisReperes' => $emploisReperes
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $emploi = new EmploiRepere();
        $form = $this->getEmploiRepereForm();
        $form->setAttribute('action', $this->url()->fromRoute('emploi-repere/ajouter', [], [], true));
        $form->bind($emploi);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEmploiRepereService()->create($emploi);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un emploi repère",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $emploi = $this->getEmploiRepereService()->getRequestedEmploiRepere($this);
        $form = $this->getEmploiRepereForm();
        $form->setAttribute('action', $this->url()->fromRoute('emploi-repere/modifier', ['emploi-repere' => $emploi->getId()], [], true));
        $form->bind($emploi);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEmploiRepereService()->update($emploi);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification de l'emploi repère",
            'emploiRepere' => $emploi,
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function afficherAction() : ViewModel
    {
        $emploi = $this->getEmploiRepereService()->getRequestedEmploiRepere($this);

        return new ViewModel([
            'emploiRepere' => $emploi,
        ]);
    }

    public function historiserAction(): Response
    {
        $emploi = $this->getEmploiRepereService()->getRequestedEmploiRepere($this);
        $this->getEmploiRepereService()->historise($emploi);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('emploi-repere', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $emploi = $this->getEmploiRepereService()->getRequestedEmploiRepere($this);
        $this->getEmploiRepereService()->restore($emploi);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('emploi-repere', [], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $emploi = $this->getEmploiRepereService()->getRequestedEmploiRepere($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getEmploiRepereService()->delete($emploi);
            exit();
        }

        $vm = new ViewModel();
        if ($emploi !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'emploi-repère " . $emploi->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('emploi-repere/supprimer', ["emploi-repere" => $emploi->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function modifierBisAction() : ViewModel
    {
        $emploi = $this->getEmploiRepereService()->getRequestedEmploiRepere($this);

        return new ViewModel([
            'emploi' => $emploi,
        ]);
    }

    public function updateLibelleAction(): JsonModel
    {
        $emploi = $this->getEmploiRepereService()->getRequestedEmploiRepere($this);
        $libelle = $this->params()->fromRoute('libelle');

        $emploi->setLibelle($libelle);
        $this->getEmploiRepereService()->update($emploi);

        return new JsonModel(['libelle' => $emploi->getLibelle()]);
    }


}
