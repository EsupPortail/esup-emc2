<?php

namespace Application\Controller\Structure;

use Application\Entity\Db\Structure;
use Application\Form\Structure\StructureFormAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use Utilisateur\Entity\Db\Role;
use Utilisateur\Entity\Db\User;
use Utilisateur\Service\Role\RoleServiceAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StructureController extends AbstractActionController {
    use RoleServiceAwareTrait;
    use StructureServiceAwareTrait;
    use StructureFormAwareTrait;
    use UserServiceAwareTrait;

    public function indexAction()
    {
        $structures = $this->getStructureService()->getStructuresOuvertes();

        return new ViewModel([
            'structures' => $structures,
        ]);
    }

    public function creerAction()
    {
        $structure = new Structure();
        $form = $this->getStructureForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/creer',[],[],true));
        $form->bind($structure);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $structure->setSource("PrEECoG");
                $this->getStructureService()->create($structure);
                $this->redirect()->toRoute('structure', [], [], true);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajouter une structure",
            'form' => $form,
            ]);
        return $vm;
    }

    public function modifierAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
        $form = $this->getStructureForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/modifier',['structure' => $structure->getId()],[],true));
        $form->bind($structure);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getStructureService()->update($structure);
                $this->redirect()->toRoute('structure', [], [], true);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modifier une structure",
            'form' => $form,
        ]);
        return $vm;
    }

    public function descriptionAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');

        return new ViewModel([
            'title' => "Description de la structure <strong>". $structure->getLibelleCourt()."</strong>",
            'description' => $structure->getDescription(),
        ]);
    }

    public function historiserAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
        $this->getStructureService()->historise($structure);
        $this->redirect()->toRoute('structure',[], [], true);
    }

    public function restaurerAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
        $this->getStructureService()->restore($structure);
        $this->redirect()->toRoute('structure',[], [], true);
    }

    public function detruireAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
        $this->getStructureService()->delete($structure);
        $this->redirect()->toRoute('structure',[], [], true);
    }

    public function synchroniserAction()
    {
        $result = $this->getStructureService()->synchroniseFromOctopus();
        $this->redirect()->toRoute('structure',[], [], true);
    }

    public function synchroniserCronAction()
    {
        $result = $this->getStructureService()->synchroniseFromOctopus();
        //mailing
    }

    public function ajouterGestionnaireAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');
        if ($structure) {

            /** @var Request $request */
            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                $gestionnaire = $this->getUserService()->getUtilisateur($data['gestionnaire']);
                if ($gestionnaire) {
                    $structure->addGestionnaire($gestionnaire);
                    $this->getStructureService()->update($structure);
                }
            } else {
                /** @var User[] $gestionnaires */
                $roleGestionnaire = $this->getRoleService()->getRoleByCode(Role::GESTIONNAIRE);
                $gestionnaires = array_diff($this->getUserService()->getUtilisateursByRole($roleGestionnaire),$structure->getGestionnaires());
                return new ViewModel([
                    'title' => 'Ajout d \'un gestionnaire', // pour ['.$composante->getLibelle().']',
                    'structure' => $structure,
                    'gestionnaires' => $gestionnaires,
                ]);
            }

        } else {
            throw new RuntimeException("Aucune structure de remontée !");
        }

        exit;
    }

    public function retirerGestionnaireAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this, 'structure');

        if ($structure) {
            /** @var Request $request */
            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                $gestionnaire = $this->getUserService()->getUtilisateur($data['gestionnaire']);
                if ($gestionnaire) {
                    $structure->removeGestionnaire($gestionnaire);
                    $this->getStructureService()->update($structure);
                }
            } else {
                /** @var User[] $gestionnaires */
                $gestionnaires = $structure->getGestionnaires();
                return new ViewModel([
                    'title' => 'Retrait d \'un gestionnaire', // pour ['.$composante->getLibelle().']',
                    'structure' => $structure,
                    'gestionnaires' => $gestionnaires,
                ]);
            }

        } else {
            throw new RuntimeException("Aucune composante de remontée !");
        }

        exit;
    }

}