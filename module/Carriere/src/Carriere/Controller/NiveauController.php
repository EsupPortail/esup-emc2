<?php

namespace Carriere\Controller;

use Carriere\Entity\Db\Niveau;
use Carriere\Form\Niveau\NiveauFormAwareTrait;
use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class NiveauController extends AbstractActionController {
    use NiveauServiceAwareTrait;
    use NiveauFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $niveaux = $this->getNiveauService()->getNiveaux('niveau', 'ASC', true);
        return new ViewModel([
            'niveaux' => $niveaux,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $niveau = new Niveau();

        $form = $this->getNiveauForm();
        $form->setAttribute('action', $this->url()->fromRoute('niveau/ajouter', [], [], true));
        $form->bind($niveau);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getNiveauService()->create($niveau);
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un niveau",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $niveau = $this->getNiveauService()->getRequestedNiveau($this);

        $form = $this->getNiveauForm();
        $form->setAttribute('action', $this->url()->fromRoute('niveau/modifier', ['niveau' => $niveau->getId()], [], true));
        $form->bind($niveau);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getNiveauService()->update($niveau);
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'un niveau",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction() : Response
    {
        $niveau = $this->getNiveauService()->getRequestedNiveau($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getNiveauService()->historise($niveau);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('niveau');
    }

    public function restaurerAction(): Response
    {
        $niveau = $this->getNiveauService()->getRequestedNiveau($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getNiveauService()->restore($niveau);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('niveau');
    }

    public function supprimerAction(): ViewModel
    {
        $niveau = $this->getNiveauService()->getRequestedNiveau($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getNiveauService()->delete($niveau);
            exit();
        }

        $vm = new ViewModel();
        if ($niveau !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du niveau " . $niveau->getEtiquette(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('niveau/supprimer', ["niveau" => $niveau->getId()], [], true),
            ]);
        }
        return $vm;
    }
}