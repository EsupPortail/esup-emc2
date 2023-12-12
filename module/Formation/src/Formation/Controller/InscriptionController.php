<?php

namespace Formation\Controller;

use Formation\Entity\Db\Inscription;
use Formation\Form\Inscription\InscriptionFormAwareTrait;
use Formation\Provider\Source\Sources;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\Inscription\InscriptionServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */
class InscriptionController extends AbstractActionController
{

    use InscriptionServiceAwareTrait;
    use InscriptionFormAwareTrait;
    use FormationInstanceServiceAwareTrait;

    public function indexAction(): ViewModel
    {
        $inscriptions = $this->getInscriptionService()->getInscriptions('histoCreation', 'DESC', true);

        return new ViewModel([
            'inscriptions' => $inscriptions,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $session = $this->getFormationInstanceService()->getRequestedFormationInstance($this, 'session');

        $inscription = new Inscription();
        if ($session) $inscription->setSession($session);
        $inscription->setSource(Sources::EMC2);

        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/ajouter', ['session' => ($session)?$session->getId():null], [], true));
        $form->bind($inscription);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($inscription->getIndividu() !== null) {
                    $inscription->setIdSource($inscription->getSession()->getId() . '_' . $inscription->getIndividu()->getId());
                    $this->getInscriptionService()->create($inscription);
                }
            }
            exit();
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une inscription",
            'form' => $form,
        ]);
        $vm->setTemplate('formation/inscription/modifier');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/modifier', ['inscription' => $inscription->getId()], [], true));
        $form->bind($inscription);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($inscription->getIndividu() !== null) {
                    $inscription->setIdSource($inscription->getSession()->getId() . '_' . $inscription->getIndividu()->getId());
                    $this->getInscriptionService()->update($inscription);
                }
            }
            exit();
        }

        $vm = new ViewModel([
            'title' => "Modificaiton de l'inscription",
            'form' => $form,
        ]);
        $vm->setTemplate('formation/inscription/modifier');
        return $vm;
    }

    public function afficherAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        return new ViewModel([
            'inscription' => $inscription,
        ]);
    }

    public function historiserAction(): Response
    {
        $inscrit = $this->getInscriptionService()->getRequestedInscription($this);
        $this->getInscriptionService()->historise($inscrit);
        $this->flashMessenger()->addSuccessMessage("L'inscription de <strong>" . $inscrit->getStagiaireDenomination() . "</strong> vient d'être retirée des listes.");

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getSession()->getId()], ['fragment' => 'inscriptions'], true);
    }

    public function restaurerAction(): Response
    {
        $inscrit = $this->getInscriptionService()->getRequestedInscription($this);
        $this->getInscriptionService()->restore($inscrit);
        $this->flashMessenger()->addSuccessMessage("L'inscription de <strong>" . $inscrit->getStagiaireDenomination() . "</strong> vient d'être restaurée.");

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getSession()->getId()], [], true);

    }

    public function supprimerAction(): ViewModel
    {
        $inscrit = $this->getInscriptionService()->getRequestedInscription($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getInscriptionService()->delete($inscrit);
            exit();
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/confirmation');
        if ($inscrit !== null) {
            $vm->setVariables([
                'title' => "Suppression de l'inscription de [" . $inscrit->getStagiaireDenomination() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation/inscription/supprimer', ["inscription" => $inscrit->getId()], [], true),
            ]);
        }
        return $vm;
    }


}