<?php

namespace UnicaenUtilisateur\Controller;

use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Form\Role\RoleFormAwareTrait;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RoleController extends AbstractActionController {
    use RoleServiceAwareTrait;
    use RoleFormAwareTrait;

    public function indexAction()
    {
        /** @var Role[] $roles */
        $roles = $this->getRoleService()->getRoles();

        return new ViewModel([
            'roles' => $roles,
        ]);
    }

    public function ajouterAction()
    {
        $role = new Role();
        $form = $this->getRoleForm();
        $form->setAttribute('action', $this->url()->fromRoute('role/ajouter', [], [], true));
        $form->bind($role);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRoleService()->create($role);
                //return $this->redirect()->toRoute('role', [], [], true);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-utilisateur/default/default-form');
        $vm->setVariables([
            'title' => 'Ajout d\'un nouveau rôle',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $role = $this->getRoleService()->getRequestedRole($this);
        $form = $this->getRoleForm();
        $form->setAttribute('action', $this->url()->fromRoute('role/modifier', ['role' => $role->getId()], [], true));
        $form->bind($role);
        $form->setOldLibelle($role->getLibelle());
        $form->setOldRoleId($role->getRoleId());

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRoleService()->update($role);
                //return $this->redirect()->toRoute('role', [], [], true);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-utilisateur/default/default-form');
        $vm->setVariables([
            'title' => 'Ajout d\'un nouveau rôle',
            'form' => $form,
        ]);
        return $vm;
    }

    public function supprimerAction()
    {
        $role = $this->getRoleService()->getRequestedRole($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getRoleService()->delete($role);
            //return $this->redirect()->toRoute('role', [], [], true);
            exit();
        }

        $vm = new ViewModel();
        if ($role !== null) {
            $vm->setTemplate('unicaen-utilisateur/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du rôle " . $role->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('role/supprimer', ["role" => $role->getId()], [], true),
            ]);
        }
        return $vm;
    }
}