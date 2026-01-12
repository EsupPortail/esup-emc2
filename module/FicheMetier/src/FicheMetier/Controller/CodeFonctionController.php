<?php

namespace FicheMetier\Controller;

use FicheMetier\Entity\Db\CodeFonction;
use FicheMetier\Form\CodeFonction\CodeFonctionFormAwareTrait;
use FicheMetier\Service\CodeFonction\CodeFonctionServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CodeFonctionController extends AbstractActionController
{
    use CodeFonctionServiceAwareTrait;
    use CodeFonctionFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $codesFonctions = $this->getCodeFonctionService()->getCodesFonctions(true);

        return new ViewModel([
            'codesFonctions' => $codesFonctions
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $codeFonction = $this->getCodeFonctionService()->getRequestedCodeFonction($this);

        return new ViewModel([
            'title' => "Affichage du code fonction <code>".$codeFonction->computeCode()."</code>",
            'codeFonction' => $codeFonction,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $codeFonction = new CodeFonction();
        $form = $this->getCodeFonctionForm();
        $form->setAttribute('action', $this->url()->fromRoute('code-fonction/ajouter', [], [], true));
        $form->bind($codeFonction);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $exist = $this->getCodeFonctionService()->getCodeFonctionByNiveauAndFamille($codeFonction->getNiveauFonction(), $codeFonction->getFamilleProfessionnelle());
                if (!$exist) {
                    $this->getCodeFonctionService()->create($codeFonction);
                }
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajouter un code fonction",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $codeFonction = $this->getCodeFonctionService()->getRequestedCodeFonction($this);
        $form = $this->getCodeFonctionForm();
        $form->setAttribute('action', $this->url()->fromRoute('code-fonction/modifier', ['code-fonction' => $codeFonction?->getId()], [], true));
        $form->bind($codeFonction);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCodeFonctionService()->update($codeFonction);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier un code fonction",
            'form' => $form,
            'codeFonction' => $codeFonction,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $codeFonction = $this->getCodeFonctionService()->getRequestedCodeFonction($this);
        $this->getCodeFonctionService()->historise($codeFonction);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('code-fonction');
    }

    public function restaurerAction(): Response
    {
        $codeFonction = $this->getCodeFonctionService()->getRequestedCodeFonction($this);
        $this->getCodeFonctionService()->restore($codeFonction);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('code-fonction');
    }

    public function supprimerAction(): ViewModel
    {
        $codeFonction = $this->getCodeFonctionService()->getRequestedCodeFonction($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCodeFonctionService()->delete($codeFonction);
            exit();
        }

        $vm = new ViewModel();
        if ($codeFonction !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du code fonction <code>" . $codeFonction->computeCode() . "</code>",
                'text' => "La suppression est définitive et reinitilisera dans les données liées le code fonction. Êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('code-fonction/supprimer', ["code-fonction" => $codeFonction->getId()], [], true),
            ]);
        }
        return $vm;
    }

}
