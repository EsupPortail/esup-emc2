<?php

namespace Utilisateur\Service\User;

use Utilisateur\Entity\Db\Role;
use Utilisateur\Entity\Db\User;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuth\Service\Traits\UserContextServiceAwareTrait;

class UserService
     // TODO ? renommer User
     // TODO extends \UnicaenAuth\Service\User
{
    use EntityManagerAwareTrait;
    use UserContextServiceAwareTrait;
    /**
     * @return string
     */
    public function getEntityClass()
    {
        return User::class;
    }

    /**
     * @param int $id
     * @return User
     */
    public function getUtilisateur($id)
    {
        $qb = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder("utilisateur")
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
        $utilisateurs = $this->getEntityManager()->getRepository(User::class)->findAll();
        return $utilisateurs;
    }

    /**
     * @param string $username
     * @return User
     */
    public function getUtilisateurByUsername($username)
    {
        $qb = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder("utilisateur")
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
     * @param string $texte
     * @return User[]
     */
    public function getUtilisateursByTexte($texte)
    {
        if (strlen($texte) < 2) return [];
//        $texte = Util::reduce($texte);
        $texte = strtolower($texte);
        $qb = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder("utilisateur")
            ->andWhere("LOWER(utilisateur.displayName) LIKE :critere")
            ->setParameter("critere", '%'.$texte.'%')
        ;
        $utilisateurs = $qb->getQuery()->getResult();
        return $utilisateurs;
    }

    /**
     * @param User $utilisateur
     * @return User
     */
    public function updateUser($utilisateur)
    {
        $this->getEntityManager()->persist($utilisateur);
        try {
            $this->getEntityManager()->flush($utilisateur);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un erreur est survenue lors de la mise à jour de l'utilisateur [".$utilisateur->getId()."]");
        }
        return $utilisateur;
    }

    /**
     * @param string $username
     * @return User
     */
    public function exist($username)
    {
        $qb = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder("utilisateur")
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
    public function changerStatus($utilisateur)
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
            } catch (OptimisticLockException $e) {
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
    public function addRole($utilisateur, $role) {
        $role->addUser($utilisateur);
        $utilisateur->addRole($role);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de l'ajout du rôle [".$role->getId()."] à l'utilisateur [".$utilisateur->getId()."]");
        }
        return $utilisateur;
    }

    /**
     * @param User $utilisateur
     * @param Role $role
     * @return User
     */
    public function removeRole($utilisateur, $role)
    {
        $role->removeUser($utilisateur);
        $utilisateur->removeRole($role);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors du retrait du rôle [".$role->getId()."] à l'utilisateur [".$utilisateur->getId()."]");
        }
        return $utilisateur;
    }

    /**
     * @param User $utilisateur
     */
    public function supprimer($utilisateur)
    {
        $this->getEntityManager()->remove($utilisateur);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la suppression de l'utilisateur [".$utilisateur->getId()."]");
        }
    }

    public function getConnectedUser()
    {
        $identity = $this->serviceUserContext->getIdentity();
        if ($identity) {
            $userIdentity = current(array_filter($identity));
            $userLogin = $userIdentity->getSupannAliasLogin();
            $user = $this->getUtilisateurByUsername($userLogin);
            return $user;
        }
        return null;
    }

    public function getConnectedRole()
    {
        $dbRole = $this->serviceUserContext->getSelectedIdentityRole();
        return $dbRole;
    }

    /**
     * @param Role $role
     * @return User[]
     */
    public function getUtilisateursByRole($role)
    {
        $qb = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder('user')
            ->addSelect('role')->join('user.roles', 'role')
            ->andWhere('role.roleId = :role')
            ->setParameter('role', $role->getRoleId())
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $role
     * @return User[]
     */
    public function getUtilisateursByRoleId($role)
    {
        $qb = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder('user')
            ->addSelect('role')->join('user.roles', 'role')
            ->andWhere('role.roleId = :role')
            ->setParameter('role', $role)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getServiceUserContext()
    {
        return $this->serviceUserContext;
    }
}

