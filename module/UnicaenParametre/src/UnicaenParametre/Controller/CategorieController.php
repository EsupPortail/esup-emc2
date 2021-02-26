<?php

namespace UnicaenParametre\Controller;

use UnicaenParametre\Entity\Db\Categorie;
use UnicaenParametre\Form\Categorie\CategorieFormAwareTrait;
use UnicaenParametre\Service\Categorie\CategorieServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CategorieController extends AbstractActionController {
    use CategorieServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use CategorieFormAwareTrait;

    public function indexAction()
    {
        $categories = $this->getCategorieService()->getCategories();
        $parametres = $this->getParametreService()->getParametres();
        $selection = $this->getCategorieService()->getRequestedCategorie($this);

        return new ViewModel([
            'categories' => $categories,
            'parametres' => $parametres,
            'selection' => $selection,
        ]);
    }

    public function ajouterAction() {

        $categorie = new Categorie();
        $form = $this->getCategorieForm();
        $form->setAttribute('action', $this->url()->fromRoute('parametre/categorie/ajouter', [], [], true));
        $form->bind($categorie);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCategorieService()->create($categorie);
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une catégorie de paramètre",
            'form' => $form,
        ]);
        $vm->setTemplate('unicaen-parametre/default/default-form');
        return $vm;
    }

    public function modifierAction()
    {
        $categorie = $this->getCategorieService()->getRequestedCategorie($this);
        $form = $this->getCategorieForm();
        $form->setOldCode($categorie->getCode());
        $form->setAttribute('action', $this->url()->fromRoute('parametre/categorie/modifier', ['categorie' => $categorie->getId()], [], true));
        $form->bind($categorie);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCategorieService()->update($categorie);
            }
        }

        $vm = new ViewModel([
            'title' => "Modification de la catégorie [".$categorie->getCode()."] ",
            'form' => $form,
        ]);
        $vm->setTemplate('unicaen-parametre/default/default-form');
        return $vm;
    }

    public function supprimerAction()
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
            $vm->setTemplate('unicaen-parametre/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la catégorie de  paramètre [" . $categorie->getCode() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('parametre/categorie/supprimer', ["categorie" => $categorie->getId()], [], true),
            ]);
        }
        return $vm;
    }


}