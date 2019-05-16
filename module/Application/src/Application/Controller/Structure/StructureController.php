<?php

namespace Application\Controller\Structure;

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
    use UserServiceAwareTrait;

    public function indexAction()
    {
        $structures = $this->getStructureService()->getStructures();

        return new ViewModel([
            'structures' => $structures,
        ]);
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
                    $this->getStructureService()->addGestionnaire($structure, $gestionnaire);
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
                    $this->getStructureService()->removeGestionnaire($structure, $gestionnaire);
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