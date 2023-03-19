<?php

namespace Carriere\Controller;

use Carriere\Entity\Db\Categorie;
use Carriere\Form\Categorie\CategorieFormAwareTrait;
use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CategorieController extends AbstractActionController
{
    use CategorieServiceAwareTrait;
    use CategorieFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $categories = $this->getCategorieService()->getCategories();

        return new ViewModel([
            'categories' => $categories,
        ]);
    }

    public function afficherMetiersAction() : ViewModel
    {
        $categorie = $this->getCategorieService()->getRequestedCategorie($this);

        return new ViewModel([
            'title' => 'Métiers ayant la categorie ['. $categorie->getLibelle().']',
            'categorie' => $categorie,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $categorie = new Categorie();

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
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une catégorie',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $categorie = $this->getCategorieService()->getRequestedCategorie($this);

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
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Modifier une catégorie',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $categorie = $this->getCategorieService()->getRequestedCategorie($this);
        if ($categorie !== null) {
            $this->getCategorieService()->historise($categorie);
        }
        return $this->redirect()->toRoute('categorie', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $categorie = $this->getCategorieService()->getRequestedCategorie($this);
        if ($categorie !== null) {
            $this->getCategorieService()->restore($categorie);
        }
        return $this->redirect()->toRoute('categorie', [], [], true);
    }

    public function supprimerAction() : ViewModel
    {
        $categorie = $this->getCategorieService()->getRequestedCategorie($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCategorieService()->delete($categorie);
            exit();
        }

        $vm = new ViewModel();
        if ($categorie !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'une catégorie " . $categorie->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('categorie/supprimer', ["categorie" => $categorie->getId()], [], true),
            ]);
        }
        return $vm;
    }
}