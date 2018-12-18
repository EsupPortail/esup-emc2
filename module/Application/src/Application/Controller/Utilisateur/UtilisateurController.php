<?php

namespace Application\Controller\Utilisateur;

use Application\Entity\Db\Role;
use Application\Entity\Db\User;
use Application\Service\Role\RoleServiceAwareTrait;
use Application\Service\User\UserServiceAwareTrait;
use Mailing\Service\Mailing\MailingServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenLdap\Entity\People;
use UnicaenLdap\Exception;
use UnicaenLdap\Filter\People as PeopleFilter;
use UnicaenLdap\Service\LdapPeopleServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * @author jean-Philippe Metivier <jean-philippe.metivier at unicaen.fr>
 */
class UtilisateurController extends AbstractActionController {
    use MailingServiceAwareTrait;
    use RoleServiceAwareTrait;
    use UserServiceAwareTrait;
    use EntityManagerAwareTrait;
    use LdapPeopleServiceAwareTrait;

    public function  indexAction()
    {
        $roles = $this->getRoleService()->getRoles();
        $utilisateur = null;
        $rolesAffectes = [];
        $rolesDisponibles = [];

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($request->getPost()->toArray()['ldap']['id'] !== null) {
                $people = $this->ldapPeopleService->get($request->getPost()->toArray()['ldap']['id']);

                $utilisateur = new User();

                $tmp_name = $people->get('sn');
                if (!is_string($tmp_name)) $tmp_name = implode("-",$people->get('sn'));
                $utilisateur->setDisplayName($tmp_name . " ". $people->get('givenName'));
                $utilisateur->setUsername($people->get('supannAliasLogin'));
                $utilisateur->setEmail($people->get('mail'));
                $utilisateur->setPassword('ldap');
                $utilisateur->setState(1);

                $params = [];
                if (!$this->userService->exist($utilisateur->getUsername())) {
                    $this->userService->updateUser($utilisateur);
                    $params = array_merge($params, ["query" => ["id" => $utilisateur->getId()] ]);
                } else {
                    $old = $this->getUserService()->getUtilisateurByUsername($utilisateur->getUsername());
                    $params = array_merge($params, ["query" => ["id" => $old->getId()] ]);
                    $this->flashMessenger()->addErrorMessage('Utilisateur <strong>'.$utilisateur->getUsername().'</strong> déjà enregistré en base.');
                }
                return $this->redirect()->toUrl($this->url()->fromRoute(null, [], $params, true));
            }
            $data = $request->getPost()->toArray()['utilisateur'];
            $utilisateur = $this->getUserService()->getUtilisateur($data['id']);
            $params = [];
            if ($utilisateur !== null) $params = ["query" => ["id" => $data['id']]];
            $this->redirect()->toRoute(null, [], $params, true);
        }

        $utilisateurId = $this->params()->fromQuery("id");
        if ($utilisateurId !== null) {
            /** @var User $utilisateur */
            $utilisateur = $this->getUserService()->getUtilisateur($utilisateurId);
            if ($utilisateur !== null) {
                $rolesAffectes = $utilisateur->getRoles()->toArray();
                $rolesDisponibles = array_diff($roles, $rolesAffectes);
            }
        }

        return new ViewModel([
            'utilisateur' => $utilisateur,
            'roles' => $roles,
            'rolesAffectes' => $rolesAffectes,
            'rolesDisponibles' => $rolesDisponibles,
        ]);
    }

    public function addRoleAction() {

        $role = null;
        $utilisateur = null;

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            /**
             * @var User $utilisateur
             * @var Role $role
             */
            $utilisateurId = $this->params()->fromRoute("utilisateur");
            $utilisateur = $this->getUserService()->getUtilisateur($utilisateurId);
            $roleId = $this->params()->fromRoute("role");
            $role = $this->getRoleService()->getRole($roleId);

            if ($utilisateur !== null && $role !== null) {
                $this->getUserService()->addRole($utilisateur, $role);
                $this->getMailingService()->notificationChangementRole($utilisateur, $role, "ajout");
            }
        }

        return new ViewModel([
            "role" => $role,
            "utilisateur" => $utilisateur,
        ]);
    }

    public function removeRoleAction() {

        $role = null;
        $utilisateur = null;

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            /**
             * @var User $utilisateur
             * @var Role $role
             */
            $utilisateurId = $this->params()->fromRoute("utilisateur");
            $utilisateur = $this->getUserService()->getUtilisateur($utilisateurId);
            $roleId = $this->params()->fromRoute("role");
            $role = $this->getRoleService()->getRole($roleId);

            if ($utilisateur !== null && $role !== null) {
                $this->getUserService()->removeRole($utilisateur,$role);
                $this->getMailingService()->notificationChangementRole($utilisateur, $role, "retrait");
            }
        }

        return new ViewModel([
            "role" => $role,
            "utilisateur" => $utilisateur,
        ]);
    }

    public function changerStatusAction() {
        /** @var User $utilisateur */
        $utilisateurId = $this->params()->fromRoute("utilisateur");
        $utilisateur = $this->getUserService()->getUtilisateur($utilisateurId);
        $utilisateur = $this->getUserService()->changerStatus($utilisateur);

        $params = [];
        if ($utilisateur !== null) $params = ["query" => ["id" => $utilisateur->getId()]];
        $this->redirect()->toRoute('utilisateur-preecog', [], $params, true);
    }

    public function effacerAction() {
        /** @var User $utilisateur */
        $utilisateurId = $this->params()->fromRoute("utilisateur");
        $utilisateur = $this->getUserService()->getUtilisateur($utilisateurId);
        $this->getUserService()->supprimer($utilisateur);

        $this->redirect()->toRoute('utilisateur-preecog', [], [], true);
    }

    /**
     * @return JsonModel
     */
    public function rechercherUtilisateurAction()
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $utilisateurs = $this->getUserService()->getUtilisateursByTexte($term);
            $result = [];
            /** @var User $utilisateur */
            foreach ($utilisateurs as $utilisateur) {
                $result[] = array(
                    'id'    => $utilisateur->getId(),
                    'label' => $utilisateur->getDisplayName(),
                    'extra' => "<span class='badge' style='background-color: slategray;'>".$utilisateur->getEmail()."</span>",
                );
            }
            usort($result, function($a, $b) {
                return strcmp($a['label'], $b['label']);
            });

            return new JsonModel($result);
        }
        exit;
    }

    /**
     * @return JsonModel
     */
    public function rechercherPeopleAction()
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $filter = PeopleFilter::orFilter(
                PeopleFilter::username($term),
                PeopleFilter::nameContains($term)
            );
            /** @var \UnicaenLdap\Service\People $ldapService */
            try {
                $collection = $this->ldapPeopleService->search($filter);
            } catch (Exception $e) {
                throw new RuntimeException("Un exception ldap est survenue :", $e);
            }
            $result = [];
            /** @var People $people */
            foreach ($collection as $people) {
                // mise en forme attendue par l'aide de vue FormSearchAndSelect
                $label = strtoupper(implode(', ', (array)$people->get('sn'))) . ' ' . $people->get('givenName');
                $result[] = array(
                    'id'    => $people->getId(),     // identifiant unique de l'item
                    'label' => $label,               // libellé de l'item
                    'extra' => "<span class='badge' style='background-color: slategray;'>".$people->get('mail')."</span>", // infos complémentaires (facultatives) sur l'item
                );
            }
            uasort($result, function($a, $b) {
                return strcmp($a['label'], $b['label']);
            });
            return new JsonModel($result);
        }
        exit;
    }

}