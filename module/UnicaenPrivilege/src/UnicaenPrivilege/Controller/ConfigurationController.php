<?php

namespace UnicaenPrivilege\Controller;

use UnicaenPrivilege\Entity\Db\CategoriePrivilege;
use UnicaenPrivilege\Entity\Db\Privilege;
use UnicaenPrivilege\Form\CategoriePrivilege\CategoriePrivilegeFormAwareTrait;
use UnicaenPrivilege\Form\Privilege\PrivilegeFormAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ConfigurationController extends AbstractActionController {
    use PrivilegeServiceAwareTrait;
    use CategoriePrivilegeFormAwareTrait;
    use PrivilegeFormAwareTrait;

    public function indexConfigurationCategorieAction() {
        /** @var CategoriePrivilege[] $categories */
        $categories = $this->getPrivilegeService()->getCategories('ordre');

        return new ViewModel([
            'categories' => $categories,
        ]);
    }

    public function creerCategorieAction() {
        $categorie = new CategoriePrivilege();
        $form = $this->getCategoriePrivilegeForm();
        $form->setAttribute('action', $this->url()->fromRoute('configuration-categorie/creer', [], [], true));
        $form->bind($categorie);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getPrivilegeService()->createCategorie($categorie);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-privilege/default/default-form');
        $vm->setVariables([
           'title' => "Ajout d'une nouvelle catégorie de privilège",
           'form' => $form,
        ]);
        return $vm;
    }

    public function gererCategorieAction() {
        $categorie = $this->getPrivilegeService()->getRequestedCategorie($this);

        return new ViewModel([
            'categorie' => $categorie,
        ]);
    }

    public function modifierCategorieAction() {
        $categorie = $this->getPrivilegeService()->getRequestedCategorie($this);
        $form = $this->getCategoriePrivilegeForm();
        $form->setAttribute('action', $this->url()->fromRoute('configuration-categorie/modifier', ['categorie' => $categorie->getId()], [], true));
        $form->bind($categorie);
        $form->setOldLibelle($categorie->getLibelle());
        $form->setOldCode($categorie->getCode());

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getPrivilegeService()->updateCategorie($categorie);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-privilege/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une catégorie de privilège",
            'form' => $form,
        ]);
        return $vm;
    }

    public function detruireCategorieAction() {
        $categorie = $this->getPrivilegeService()->getRequestedCategorie($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getPrivilegeService()->deleteCategorie($categorie);
            //return $this->redirect()->toRoute('configuration-categorie', [], [] , true);
            exit();
        }

        $vm = new ViewModel();
        if ($categorie !== null) {
            $vm->setTemplate('unicaen-privilege/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la Catégorie de privilège  de " . $categorie->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('configuration-categorie/detruire', ["categorie" => $categorie->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function creerPrivilegeAction()
    {
        $categorie = $this->getPrivilegeService()->getRequestedCategorie($this);
        $privilege = new Privilege();
        $form = $this->getPrivilegeForm();
        $form->setAttribute('action', $this->url()->fromRoute('configuration-privilege/creer', ['categorie' => $categorie->getId()], [], true));
        $form->bind($privilege);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $privilege->setCategorie($categorie);
                $this->getPrivilegeService()->createPrivilege($privilege);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-privilege/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'unnouveau privilège",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierPrivilegeAction() {
        $privilege = $this->getPrivilegeService()->getRequestedPrivilege($this);
        $form = $this->getPrivilegeForm();
        $form->setAttribute('action', $this->url()->fromRoute('configuration-privilege/modifier', ['privilege' => $privilege->getId()], [], true));
        $form->bind($privilege);
        $form->setOldLibelle($privilege->getLibelle());
        $form->setOldCode($privilege->getCode());

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getPrivilegeService()->updatePrivilege($privilege);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-privilege/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un privilège",
            'form' => $form,
        ]);
        return $vm;
    }

    public function detruirePrivilegeAction() {
        $privilege = $this->getPrivilegeService()->getRequestedPrivilege($this);
        $categorie = $privilege->getCategorie();

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getPrivilegeService()->deletePrivilege($privilege);
            //return $this->redirect()->toRoute('configuration-categorie/gerer', ['categorie' => $categorie->getId()], [] , true);
            exit();
        }

        $vm = new ViewModel();
        if ($privilege !== null) {
            $vm->setTemplate('unicaen-privilege/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du privilège " . $privilege->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('configuration-privilege/detruire', ["privilege" => $privilege->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function providerAction() {
        $categorie = $this->getPrivilegeService()->getRequestedCategorie($this);

        return new ViewModel([
            'title' => "Affichage du fichier fournissant la déclaration des privilèges",
            'categorie' => $categorie,
        ]);
    }
}