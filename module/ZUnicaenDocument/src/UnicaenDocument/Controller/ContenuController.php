<?php

namespace UnicaenDocument\Controller;

use UnicaenDocument\Entity\Db\Content;
use UnicaenDocument\Form\Contenu\ContenuFormAwareTrait;
use UnicaenDocument\Service\Contenu\ContenuServiceAwareTrait;
use UnicaenDocument\Service\Macro\MacroServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContenuController extends AbstractActionController {
    use ContenuServiceAwareTrait;
    use MacroServiceAwareTrait;
    use ContenuFormAwareTrait;

    public function indexAction()
    {
        $contenus = $this->getContenuService()->getContenus();

        return new ViewModel([
            'contenus' => $contenus,
        ]);
    }

    public function afficherAction()
    {
        $contenu = $this->getContenuService()->getRequestedContenu($this);

        return new ViewModel([
            'title' => "Affichage du contenu",
            'contenu' => $contenu,
        ]);
    }

    public function ajouterAction()
    {
        $contenu = new Content();

        $form = $this->getContenuForm();
        $form->setAttribute('action', $this->url()->fromRoute('contenu/contenu/ajouter', [], [], true));
        $form->bind($contenu);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getContenuService()->create($contenu);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-document/contenu/modifier');
        $vm->setVariables([
            'title' => "Création d'un contenu",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $contenu = $this->getContenuService()->getRequestedContenu($this);

        $form = $this->getContenuForm();
        $form->setAttribute('action', $this->url()->fromRoute('contenu/contenu/modifier', ['contenu' => $contenu->getId()], [], true));
        $form->bind($contenu);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getContenuService()->update($contenu);
            }
        }

        return new ViewModel([
            'title' => "Modification d'un contenu",
            'form' => $form,
        ]);
    }

    public function historiserAction()
    {
        $contenu = $this->getContenuService()->getRequestedContenu($this);
        $this->getContenuService()->historise($contenu);

        return $this->redirect()->toRoute('contenu/contenu', [], [], true);
    }

    public function restaurerAction()
    {
        $contenu = $this->getContenuService()->getRequestedContenu($this);
        $this->getContenuService()->restore($contenu);

        return $this->redirect()->toRoute('contenu/contenu', [], [], true);
    }

    public function detruireAction()
    {
        $contenu = $this->getContenuService()->getRequestedContenu($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getContenuService()->delete($contenu);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($contenu !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du contenu [" . $contenu->getCode() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('contenu/contenu/detruire', ["contenu" => $contenu->getId()], [], true),
            ]);
        }
        return $vm;
    }
}