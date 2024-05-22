<?php

namespace  Structure\Controller;

use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Structure\Entity\Db\Observateur;
use Structure\Form\Observateur\ObservateurFormAwareTrait;
use Structure\Service\Observateur\ObservateurServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class ObservateurController extends AbstractActionController {
    use ObservateurServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;
    use ObservateurFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $observateurs = $this->getObservateurService()->getObservateurs(true);

        return new ViewModel([
            'observateurs' => $observateurs,
        ]);
    }

    public function indexObservateurAction(): ViewModel
    {
        $connectedUser = $this->getUserService()->getConnectedUser();
        $observateurs = $this->getObservateurService()->getObservateursByUtilisateur($connectedUser);

        $structures = []; $observateursByStructures = [];
        foreach ($observateurs as $observateur) {
            $structure = $observateur->getStructure();
            $structures[$structure->getId()] = $structure;
            $observateursByStructures[$structure->getId()][] = $observateur;
        }

        return new ViewModel([
            'user' => $connectedUser,
            'observateurs' => $observateurs,
            'structures' => $structures,
            'observateursByStructures' => $observateursByStructures,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $observateur = $this->getObservateurService()->getRequestedObservateur($this);

        return new ViewModel([
            'title' => "Affiche de l'observateur·trice",
            'observateur' => $observateur,
        ]);
    }

        public function ajouterAction(): ViewModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $observateur = new Observateur();
        if ($structure != null) {
            $observateur->setStructure($structure);
        }
        $form = $this->getObservateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/observateur/ajouter', ['structure' => $structure?->getId()], [], true));
        $form->bind($observateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservateurService()->create($observateur);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un·e observateur·trce",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }


    public function modifierAction(): ViewModel
    {
        $observateur = $this->getObservateurService()->getRequestedObservateur($this);

        $form = $this->getObservateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/observateur/modifier', ['observateur' => $observateur->getId()], [], true));
        $form->bind($observateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservateurService()->update($observateur);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification de l'observateur·trce",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $observateur = $this->getObservateurService()->getRequestedObservateur($this);
        $this->observateurService->historise($observateur);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('structure/observateur', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $observateur = $this->getObservateurService()->getRequestedObservateur($this);
        $this->observateurService->restore($observateur);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('structure/observateur', [], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $observateur = $this->getObservateurService()->getRequestedObservateur($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getObservateurService()->delete($observateur);
            exit();
        }

        $vm = new ViewModel();
        if ($observateur !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'observateur·trice",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('structure/observateur/supprimer', ["observateur" => $observateur->getId()], [], true),
            ]);
        }
        return $vm;
    }
}