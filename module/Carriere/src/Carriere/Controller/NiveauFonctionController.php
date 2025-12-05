<?php

namespace Carriere\Controller;

use Carriere\Entity\Db\NiveauFonction;
use Carriere\Form\NiveauFonction\NiveauFonctionFormAwareTrait;
use Carriere\Service\NiveauFonction\NiveauFonctionServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */

class NiveauFonctionController extends AbstractActionController
{
    use NiveauFonctionServiceAwareTrait;
    use NiveauFonctionFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $niveauxFonctions = $this->getNiveauFonctionService()->getNiveauxFonctions(true);

        return new ViewModel([
            'niveauxFonctions' => $niveauxFonctions,
            'service' => $this->getNiveauFonctionService(),
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $niveauFonction = $this->getNiveauFonctionService()->getRequestedNiveauFonction($this);
        $codesEmploisTypes = $this->getNiveauFonctionService()->getCodesEmploisTypesByCodeFonction($niveauFonction);

        return new ViewModel([
            'title' => "Affichage du niveau de fonction",
            'niveauFonction' => $niveauFonction,
            'codesEmploisTypes' => $codesEmploisTypes,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $fonctionType = new NiveauFonction();

        $form = $this->getNiveauFonctionForm();
        $form->setAttribute('action', $this->url()->fromRoute('niveau-fonction/ajouter', [], [], true));
        $form->bind($fonctionType);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getNiveauFonctionService()->create($fonctionType);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un niveau de fonction",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $niveauFonction = $this->getNiveauFonctionService()->getRequestedNiveauFonction($this);

        $form = $this->getNiveauFonctionForm();
        $form->setAttribute('action', $this->url()->fromRoute('niveau-fonction/modifier', ['niveau-fonction' => $niveauFonction->getId()], [], true));
        $form->bind($niveauFonction);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getNiveauFonctionService()->update($niveauFonction);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification du niveau de fonction",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $niveauFonction = $this->getNiveauFonctionService()->getRequestedNiveauFonction($this);
        $this->getNiveauFonctionService()->historise($niveauFonction);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('niveau-fonction', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $niveauFonction = $this->getNiveauFonctionService()->getRequestedNiveauFonction($this);
        if ($this->getNiveauFonctionService()->isCodeDisponible($niveauFonction)) {
            $this->getNiveauFonctionService()->restore($niveauFonction);
        } else {
            $this->flashMessenger()->addErrorMessage("Le code [".$niveauFonction->getCode()."] n'est pas disponible.");
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('niveau-fonction', [], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $fonctionType = $this->getNiveauFonctionService()->getRequestedNiveauFonction($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getNiveauFonctionService()->delete($fonctionType);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($fonctionType !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du code fonction [" . $fonctionType->getCode() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('niveau-fonction/supprimer', ["niveau-fonction" => $fonctionType->getId()], [], true),
            ]);
        }
        return $vm;
    }
}
