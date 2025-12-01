<?php

namespace Referentiel\Controller;

use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Referentiel\Entity\Db\Referentiel;
use Referentiel\Form\Referentiel\ReferentielFormAwareTrait;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;

class ReferentielController extends AbstractActionController
{
    use ReferentielServiceAwareTrait;
    use ReferentielFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $referentiels = $this->getReferentielService()->getReferentiels(true);

        return new ViewModel([
            'referentiels' => $referentiels
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $referentiel = $this->getReferentielService()->getRequestedReferentiel($this);

        return new ViewModel([
           'title' => "Affichage du referentiel [".$referentiel?->getLibelleCourt()."]",
           'referentiel' => $referentiel
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $referentiel = new Referentiel();
        $form = $this->getReferentielForm();
        $form->setAttribute('action', $this->url()->fromRoute('referentiel/ajouter', [], [], true));
        $form->bind($referentiel);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getReferentielService()->create($referentiel);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un référentiel",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $referentiel = $this->getReferentielService()->getRequestedReferentiel($this);
        $form = $this->getReferentielForm();
        $form->setAttribute('action', $this->url()->fromRoute('referentiel/modifier', ['referentiel' => $referentiel?->getId()], [], true));
        $form->bind($referentiel);
        $form->setOldLibelleCourt($referentiel->getLibelleCourt());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getReferentielService()->update($referentiel);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification du référentiel [".$referentiel?->getLibelleCourt()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $referentiel = $this->getReferentielService()->getRequestedReferentiel($this);
        $this->getReferentielService()->historise($referentiel);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        /** @see ReferentielController::indexAction() */
        return $this->redirect()->toRoute('referentiel', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $referentiel = $this->getReferentielService()->getRequestedReferentiel($this);
        $this->getReferentielService()->restore($referentiel);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        /** @see ReferentielController::indexAction() */
        return $this->redirect()->toRoute('referentiel', [], [], true);
    }

    public function supprimerAction(): ViewModel
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
                'title' => "Suppression du référentiel [".$referentiel->getLibelleCourt()."]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('referentiel/supprimer', ["referentiel" => $referentiel->getId()], [], true),
            ]);
        }
        return $vm;
    }
}
