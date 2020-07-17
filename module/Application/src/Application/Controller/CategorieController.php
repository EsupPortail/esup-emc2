<?php

namespace Application\Controller;

use Application\Entity\Db\Categorie;
use Application\Form\Categorie\CategorieForm;
use Application\Form\Categorie\CategorieFormAwareTrait;
use Application\Service\Categorie\CategorieServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CategorieController extends AbstractActionController
{
    use CategorieServiceAwareTrait;
    use CategorieFormAwareTrait;

    public function ajouterAction()
    {
        /** @var Categorie $categorie */
        $categorie = new Categorie();

        /** @var CategorieForm $form */
        $form = $this->getCategorieForm();
        $form->setAttribute('action', $this->url()->fromRoute('categorie/ajouter', [], [], true));
        $form->bind($categorie);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCategorieService()->create($categorie);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une catégorie',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $categorie = $this->getCategorieService()->getRequestedCategorie($this);

        /** @var CategorieForm $form */
        $form = $this->getCategorieForm();
        $form->setAttribute('action', $this->url()->fromRoute('categorie/modifier', ['categorie' => $categorie->getId()], [], true));
        $form->bind($categorie);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCategorieService()->update($categorie);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier une catégorie',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $categorie = $this->getCategorieService()->getRequestedCategorie($this);
        if ($categorie !== null) {
            $this->getCategorieService()->historise($categorie);
        }
        return $this->redirect()->toRoute('corps', [], ['fragment'=>'categorie'], true);
    }

    public function restaurerAction()
    {
        $categorie = $this->getCategorieService()->getRequestedCategorie($this);
        if ($categorie !== null) {
            $this->getCategorieService()->restore($categorie);
        }
        return $this->redirect()->toRoute('corps', [], ['fragment'=>'categorie'], true);
    }

    public function supprimerAction()
    {
        $categorie = $this->getCategorieService()->getRequestedCategorie($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCategorieService()->delete($categorie);
            exit();
        }

        $vm = new ViewModel();
        if ($categorie !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'une catégorie " . $categorie->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('categorie/supprimer', ["categorie" => $categorie->getId()], [], true),
            ]);
        }
        return $vm;
    }
}