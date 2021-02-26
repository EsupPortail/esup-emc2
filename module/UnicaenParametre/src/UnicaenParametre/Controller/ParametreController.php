<?php

namespace UnicaenParametre\Controller;

use UnicaenParametre\Entity\Db\Parametre;
use UnicaenParametre\Form\Parametre\ParametreFormAwareTrait;
use UnicaenParametre\Service\Categorie\CategorieServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ParametreController extends AbstractActionController {
    use CategorieServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use ParametreFormAwareTrait;

    public function ajouterAction()
    {
        $categorie = $this->getCategorieService()->getRequestedCategorie($this);
        $parametre = new Parametre();
        $parametre->setCategorie($categorie);
        $form = $this->getParametreForm();
        $form->setCategorie($categorie);
        $form->setAttribute('action', $this->url()->fromRoute('parametre/ajouter', ['categorie' => $categorie->getId()], [], true));
        $form->bind($parametre);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getParametreService()->create($parametre);
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un paramètre pour la catégorie [".$categorie->getCode()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('unicaen-parametre/default/default-form');
        return $vm;
    }

    public function modifierAction()
    {
        $parametre = $this->getParametreService()->getRequestedParametre($this);
        $form = $this->getParametreForm();
        $form->setOldCode($parametre->getCategorie()->getCode(). "-" . $parametre->getCode());
        $form->setCategorie($parametre->getCategorie());
        $form->setAttribute('action', $this->url()->fromRoute('parametre/modifier', ['parametre' => $parametre->getId()], [], true));
        $form->bind($parametre);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getParametreService()->update($parametre);
            }
        }

        $vm = new ViewModel([
            'title' => "Modification du paramètre [".$parametre->getCode()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('unicaen-parametre/default/default-form');
        return $vm;
    }

    public function supprimerAction()
    {
        $parametre = $this->getParametreService()->getRequestedParametre($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getParametreService()->delete($parametre);
            exit();
        }

        $vm = new ViewModel();
        if ($parametre !== null) {
            $vm->setTemplate('unicaen-parametre/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du paramètre [" . $parametre->getCode() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('parametre/supprimer', ["parametre" => $parametre->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function modifierValeurAction()
    {
        $parametre = $this->getParametreService()->getRequestedParametre($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $valeur = $data['valeur'];
            $parametre->setValeur($valeur);
            $this->getParametreService()->update($parametre);
        }

        return new ViewModel([
            'title' => "Modification de la valeur du paramètre <strong>". $parametre->getCode() ."</strong>",
            'parametre' => $parametre,
        ]);
    }
}