<?php

namespace Metier\Controller;

use Metier\Entity\Db\Referentiel;
use Metier\Form\Referentiel\ReferentielFormAwareTrait;
use Metier\Service\Referentiel\ReferentielServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ReferentielController extends AbstractActionController
{
    use ReferentielServiceAwareTrait;
    use ReferentielFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $referentiels = $this->getReferentielService()->getReferentiels();

        return new ViewModel([
            'referentiels' => $referentiels,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $referentiel = new Referentiel();
        $form = $this->getReferentielForm();
        $form->setAttribute('action', $this->url()->fromRoute('metier/referentiel/ajouter', [], [], true));
        $form->bind($referentiel);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getReferentielService()->create($referentiel);
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un référentiel métier",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $referentiel = $this->getReferentielService()->getRequestedReferentiel($this);
        $form = $this->getReferentielForm();
        $form->setAttribute('action', $this->url()->fromRoute('metier/referentiel/modifier', ['referentiel' => $referentiel->getId()], [], true));
        $form->bind($referentiel);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getReferentielService()->update($referentiel);
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'un référentiel métier",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction() : Response
    {
        $referentiel = $this->getReferentielService()->getRequestedReferentiel($this);
        $this->getReferentielService()->historise($referentiel);
        return $this->redirect()->toRoute('metier/referentiel', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $referentiel = $this->getReferentielService()->getRequestedReferentiel($this);
        $this->getReferentielService()->restore($referentiel);
        return $this->redirect()->toRoute('metier/referentiel', [], [], true);
    }

    public function supprimerAction() : ViewModel
    {
        $referentiel = $this->getReferentielService()->getRequestedReferentiel($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getReferentielService()->delete($referentiel);
            exit();
        }

        $vm = new ViewModel();
        if ($referentiel !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du référentiel " . $referentiel->getLibelleCourt(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('metier/referentiel/supprimer', ["metier" => $referentiel->getId()], [], true),
            ]);
        }
        return $vm;
    }
}