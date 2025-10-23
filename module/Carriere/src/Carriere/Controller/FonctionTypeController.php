<?php

namespace Carriere\Controller;

use Carriere\Entity\Db\FonctionType;
use Carriere\Form\FonctionType\FonctionTypeFormAwareTrait;
use Carriere\Service\FonctionType\FonctionTypeServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */

class FonctionTypeController extends AbstractActionController
{
    use FonctionTypeServiceAwareTrait;
    use FonctionTypeFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $fonctionsTypes = $this->getFonctionTypeService()->getFonctionsTypes(true);

        return new ViewModel([
            'fonctionsTypes' => $fonctionsTypes,
            'service' => $this->getFonctionTypeService(),
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $fonctionType = $this->getFonctionTypeService()->getRequestedFonctionType($this);

        return new ViewModel([
            'title' => "Affichage du type de fonction",
            'fonctionType' => $fonctionType,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $fonctionType = new FonctionType();

        $form = $this->getFonctionTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('fonction-type/ajouter', [], [], true));
        $form->bind($fonctionType);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFonctionTypeService()->create($fonctionType);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout du type de fonction",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $fonctionType = $this->getFonctionTypeService()->getRequestedFonctionType($this);

        $form = $this->getFonctionTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('fonction-type/modifier', ['fonction-type' => $fonctionType->getId()], [], true));
        $form->bind($fonctionType);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFonctionTypeService()->update($fonctionType);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification du type de fonction",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $fonctionType = $this->getFonctionTypeService()->getRequestedFonctionType($this);
        $this->getFonctionTypeService()->historise($fonctionType);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('fonction-type', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $fonctionType = $this->getFonctionTypeService()->getRequestedFonctionType($this);
        if ($this->getFonctionTypeService()->isCodeDisponible($fonctionType)) {
            $this->getFonctionTypeService()->restore($fonctionType);
        } else {
            $this->flashMessenger()->addErrorMessage("Le code [".$fonctionType->getCode()."] n'est pas disponible.");
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('fonction-type', [], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $fonctionType = $this->getFonctionTypeService()->getRequestedFonctionType($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getFonctionTypeService()->delete($fonctionType);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($fonctionType !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du type fonction [" . $fonctionType->getCode() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fonction-type/supprimer', ["fonction-type" => $fonctionType->getId()], [], true),
            ]);
        }
        return $vm;
    }
}
