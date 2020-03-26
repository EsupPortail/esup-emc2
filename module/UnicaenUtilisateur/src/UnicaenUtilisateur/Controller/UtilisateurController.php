<?php

namespace UnicaenUtilisateur\Controller;

use DateTime;
use Mailing\Service\Mailing\MailingServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenApp\View\Model\CsvModel;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Form\User\UserFormAwareTrait;
use UnicaenUtilisateur\Service\RechercheIndividu\RechercheIndividuServiceInterface;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class UtilisateurController extends AbstractActionController {
    use MailingServiceAwareTrait;
    use RoleServiceAwareTrait;
    use UserServiceAwareTrait;
    use EntityManagerAwareTrait;

    use UserFormAwareTrait;

    public $recherche;

    public function  indexAction()
    {
        $roles = $this->getRoleService()->getRoles();
        $utilisateur = null;
        $rolesAffectes = [];
        $rolesDisponibles = [];

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray()['utilisateur'];
            $source = explode('||',$data['id'])[0];
            $id = explode('||',$data['id'])[1];
            $label = $data['label'];

            $utilisateur = null;
            switch($source) {
                case 'app' :
                    $utilisateur = $this->getUserService()->getUtilisateur($id);
                    $params = [];
                    if ($utilisateur !== null) $params = ["query" => ["id" => $id]];
                    return $this->redirect()->toRoute(null, [], $params, true);

                //todo n'importe quelle source connue 
                default :
                    if (isset($this->recherche[$source])) {
                        $people = $this->recherche[$source]->findById($id);
                        $utilisateur = $this->getUserService()->importFromRechercheIndividuResultatInterface($people, 'ldap');

                        $params = [];
                        if (!$this->userService->exist($utilisateur->getUsername())) {
                            $this->userService->create($utilisateur);
                            $params = array_merge($params, ["query" => ["id" => $utilisateur->getId()]]);
                        } else {
                            $old = $this->getUserService()->getUtilisateurByUsername($utilisateur->getUsername());
                            $params = array_merge($params, ["query" => ["id" => $old->getId()]]);
                            $this->flashMessenger()->addErrorMessage('Utilisateur <strong>' . $utilisateur->getUsername() . '</strong> déjà enregistré en base.');
                        }
                        return $this->redirect()->toUrl($this->url()->fromRoute(null, [], $params, true));
                    }
                    else {
                        $this->flashMessenger()->addErrorMessage("Source inconnue [" . $source . "].");
                        return $this->redirect()->toRoute('utilisateur', [], [], true);
                    }
            }
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

    /** Ajout d'un utilisateur local **/
    public function ajouterAction()
    {
        $user = new User();
        $form = $this->getUserForm();
        $form->setAttribute('action', $this->url()->fromRoute('utilisateur-preecog/ajouter', [], [], true));
        $form->bind($user);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getUserService()->createLocalUser($user);
                return $this->redirect()->toRoute('utilisateur-preecog',[], ["query" => ["id" => $user->getId()]], true);
            }
        }

        $vm = new ViewModel();
        //$vm->setTemplate('unicaen-utilisateur/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un utilisateur local",
            'form' => $form,
        ]);
        return $vm;
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
                //$this->getMailingService()->notificationChangementRole($utilisateur, $role, "ajout");
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
//                $this->getMailingService()->notificationChangementRole($utilisateur, $role, "retrait");
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
        return $this->redirect()->toRoute('utilisateur-preecog', [], $params, true);
    }

    public function effacerAction() {
        /** @var User $utilisateur */
        $utilisateurId = $this->params()->fromRoute("utilisateur");
        $utilisateur = $this->getUserService()->getUtilisateur($utilisateurId);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getUserService()->supprimer($utilisateur);
            //return $this->redirect()->toRoute('utilisateur-preecog', [], [], true);
            exit();
        }

        $vm = new ViewModel();
        if ($utilisateur !== null) {
            $vm->setTemplate('unicaen-utilisateur/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'utilisation " . $utilisateur->getDisplayName(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('utilisateur-preecog/effacer', ["utilisateur" => $utilisateur->getId()], [], true),
            ]);
        }
        return $vm;
    }
    
    /** @TODO afficher si mail manquant ... */
    public function rechercherAction() {
        $term = $this->params()->fromQuery('term');
        

        /** @var RechercheIndividuServiceInterface $service
         */
        foreach ($this->recherche as $key => $service) {
            $res[$key] = $service->findByTerm($term);
        }

        $result= [];
        foreach ($res as $key => $individus) {
            foreach ($individus as $individu) {
                $result[] = array(
                    'id' => $key . '||' . $individu->getId(),
                    'label' => $individu->getDisplayName(),
                    'extra' => "<span class='badge' id='". $key ."' >" . $individu->getEmail() . "</span>",
                );
            }
        }
        return new JsonModel($result);
    }

    public function listingAction()
    {
        $users = $this->getUserService()->getUtilisateurs();
        return new ViewModel([
            'users' => $users,
        ]);
    }
    
    public function exportAction() 
    {
        $date = (new DateTime())->format("Ymd-his");
        $filemane = $date . "_listing-utilisateurs-GAETHAN.csv";
        $users = $this->getUserService()->getUtilisateurs();
        $headers = [ 'Nom d\'utilisateur', 'Nom affiché', 'Actif', 'Rôle'];

        $records = [];
        foreach ($users as $user) {
            $item  = [];
            $item[]  = $user->getUsername();
            $item[]  = $user->getDisplayName();
            $item[]  = $user->getState();

            $role_array = [];
            foreach ($user->getRoles() as $role) {
                $role_array[] = $role->getLibelle();
            }
            $item[] = implode("\n", $role_array);
            $records[] = $item;
        }

        $date = (new DateTime())->format('Ymd-His');
        $filename="export_utilisateur_".$date.".csv";
        $CSV = new CsvModel();
        $CSV->setDelimiter(';');
        $CSV->setEnclosure('"');
        $CSV->setHeader($headers);
        $CSV->setData($records);
        $CSV->setFilename($filename);
        return $CSV;    
    }
}