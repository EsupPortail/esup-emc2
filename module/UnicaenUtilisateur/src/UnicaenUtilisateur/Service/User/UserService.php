<?php

namespace UnicaenUtilisateur\Service\User;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Entity\Ldap\People;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuthentification\Service\Traits\UserContextServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Entity\Db\UserInterface;
use UnicaenUtilisateur\Exception\RuntimeException;
use UnicaenUtilisateur\Service\RechercheIndividu\RechercheIndividuResultatInterface;
use UnicaenUtilisateur\Service\RechercheIndividu\RechercheIndividuServiceInterface;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use Zend\Authentication\AuthenticationService;
use Zend\Crypt\Password\Bcrypt;

class UserService implements RechercheIndividuServiceInterface
{
    use EntityManagerAwareTrait;
    use UserContextServiceAwareTrait;
    use RoleServiceAwareTrait;

    /** @var AuthenticationService **/
    private $authenticationService;

    public function setAuthenticationService(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }
    
    private $userEntityClass;

    public function setUserEntityClass($userEntityClass)
    {
        $this->userEntityClass = $userEntityClass;
    }

    /**
     * @param int $id
     * @return User
     */
    public function getUtilisateur($id)
    {
        $qb = $this->getEntityManager()->getRepository($this->userEntityClass)->createQueryBuilder("utilisateur")
            ->andWhere("utilisateur.id = :id")
            ->setParameter("id", $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs utilisateurs partagent l'identifiant : ".$id);
        }
        return $result;
    }

    /**
     * @return User[]
     */
    public function getUtilisateurs()
    {
        $utilisateurs = $this->getEntityManager()->getRepository($this->userEntityClass)->findAll();
        return $utilisateurs;
    }

    /**
     * @param string $username
     * @return User
     */
    public function getUtilisateurByUsername($username)
    {
        $qb = $this->getEntityManager()->getRepository($this->userEntityClass)->createQueryBuilder("utilisateur")
            ->andWhere("utilisateur.username = :username")
            ->setParameter("username", $username)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs utilisateurs partage le même username [".$username."] !");
        }
        return $result;
    }

    /**
     * @param $id
     * @return User
     */
    public function findById($id)
    {
        return $this->getUtilisateur($id);
    }
    
    /**
     * @param string $texte
     * @return User[]
     */
    public function findByTerm($texte)
    {
        if (strlen($texte) < 2) return [];
        $texte = strtolower($texte);
        $qb = $this->getEntityManager()->getRepository($this->userEntityClass)->createQueryBuilder("utilisateur")
            ->andWhere("LOWER(utilisateur.displayName) LIKE :critere")
            ->setParameter("critere", '%'.$texte.'%')
            ->orderBy("utilisateur.displayName")
        ;
        $utilisateurs = $qb->getQuery()->getResult();
        return $utilisateurs;
    }

    /**
     * @param RechercheIndividuResultatInterface $individu
     * @param string $source
     * @return User
     */
    public function importFromRechercheIndividuResultatInterface(RechercheIndividuResultatInterface $individu, $source)
    {
        $utilisateur = new User();
        $utilisateur->setDisplayName($individu->getDisplayname());
        $utilisateur->setUsername($individu->getUsername());
        $utilisateur->setEmail($individu->getEmail());
        $utilisateur->setPassword($source);
        $utilisateur->setState(1);
        return $utilisateur;
    }

    /**
     * @param User $user
     * @return User
     */
    public function create($user)
    {
        try {
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush($user);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base d'un User.", $e);
        }
        return $user;
    }

    /**
     * @param User $user
     * @return User
     */
    public function createLocalUser(User $user) {
        $user->setState(1);
        $bcrypt = new Bcrypt();
        $password = $bcrypt->create($user->getPassword());
        $user->setPassword($password);
        $this->create($user);
        return $user;
    }

    /**
     * @param User $utilisateur
     * @return User
     */
    public function update(User $utilisateur)
    {
        try {
            $this->getEntityManager()->flush($utilisateur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de la mise à jour en base d'un User.", $e);
        }
        return $utilisateur;
    }

    /**
     * @param string $username
     * @return User
     */
    public function exist(string $username)
    {
        $qb = $this->getEntityManager()->getRepository($this->userEntityClass)->createQueryBuilder("utilisateur")
            ->andWhere("utilisateur.username = :username")
            ->setParameter("username", $username)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs utilisateurs partage le même username [".$username."] !");
        }
        return $result;
    }

    /**
     * @param User $utilisateur
     * @return User
     */
    public function changerStatus(User $utilisateur)
    {
        if ($utilisateur) {
            $status = $utilisateur->getState();
            if ($status == 1 ) {
                $utilisateur->setState(0);
            }
            else {
                $utilisateur->setState(1);
            }
            try {
                $this->getEntityManager()->flush($utilisateur);
            } catch (ORMException $e) {
                throw new RuntimeException("Un erreur est survenue lors du changement de status de l'utilisateur [".$utilisateur->getId()."]");
            }
        }
        return $utilisateur;
    }

    /**
     * @param User $utilisateur
     * @param Role $role
     * @return User
     */
    public function addRole($utilisateur, $role) 
    {
        $utilisateur->addRole($role);
        try {
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de l'ajout du rôle [".$role->getId()."] à l'utilisateur [".$utilisateur->getId()."]");
        }
        return $utilisateur;
    }

    /**
     * @param User $utilisateur
     * @param Role $role
     * @return User
     */
    public function removeRole(User $utilisateur, Role $role)
    {
        $utilisateur->removeRole($role);
        try {
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors du retrait du rôle [".$role->getId()."] à l'utilisateur [".$utilisateur->getId()."]");
        }
        return $utilisateur;
    }

    /**
     * @param User $utilisateur
     */
    public function supprimer(User $utilisateur)
    {
        try {
            $this->getEntityManager()->remove($utilisateur);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la suppression de l'utilisateur [".$utilisateur->getId()."]");
        }
    }

    /**
     * @param Role $role
     * @return User[]
     */
    public function getUtilisateursByRole(Role $role)
    {
        $qb = $this->getEntityManager()->getRepository($this->userEntityClass)->createQueryBuilder('user')
            ->addSelect('role')->join('user.roles', 'role')
            ->andWhere('role.roleId = :role')
            ->setParameter('role', $role->getRoleId())
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return UserInterface
     */
    public function getConnectedUser()
    {
        $identity = $this->authenticationService->getIdentity();
        if ($identity) {
            $username = null;
            if (isset($identity['ldap'])) {
                /** @var People $userIdentity */
                $userIdentity = $identity['ldap'];
                $username = $userIdentity->getSupannAliasLogin();
                $user = $this->getUtilisateurByUsername($username);
                return $user;
            }
            if (isset($identity['shib'])) {
                /** @var People $userIdentity */
                $userIdentity = $identity['shib'];
                $username = $userIdentity->getUsername();
                $user = $this->getUtilisateurByUsername($username);
                return $user;
            }
            if (isset($identity['db'])) {
                return $identity['db'];
            }
        }
        return null;
    }

    public function getConnectedRole()
    {
        $identity = $this->authenticationService->getIdentity();
        $dbRole = $this->serviceUserContext->getSelectedIdentityRole();
        return $dbRole;
    }

    public function getType() {
        /** @var People $userIdentity */
        $identity = $this->authenticationService->getIdentity();
        $userIdentity = $identity['ldap'];
        $uid = $userIdentity->getUid();

        switch($uid[0]) {
            case 'p' : return 'PERSONNEL';
            case 'e' : return 'ETUDIANT';
        }
        return 'INCONNU';
    }

    public function getUtilisateursByRoleIdAsOptions($string)
    {
        $role = $this->getRoleService()->getRoleByCode($string);
        $users = $this->getUtilisateursByRole($role);
        $array = [];
        foreach ($users as $user) {
            $array[$user->getId()] = $user->getDisplayName();
        }
        return $array;
    }
}

