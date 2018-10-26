<?php

namespace Application\Service\User;

use Application\Entity\Db\Service;
use Application\Entity\Db\User;
use Application\Service\CommonServiceAbstract;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenApp\Util;

/**
 * @author PROUX-DELROUYRE Guillaume <guillaume.proux-delrouyre at unicaen.fr>
 * @author jean-Philippe Metivier <jean-philippe.metivier at unicaen.fr>
 */
class UserService
{
    use EntityManagerAwareTrait;
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
        $texte = Util::reduce($texte);
        $qb = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder("utilisateur")
            ->andWhere("utilisateur.displayName LIKE :critere")
            ->setParameter("critere", '%'.$texte.'%')
        ;
        $utilisateurs = $qb->getQuery()->getResult();
        return $utilisateurs;
    }

    /**
     * @param User $utilisateur
     * @return User
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateUser($utilisateur)
    {
        $this->getEntityManager()->persist($utilisateur);
        $this->getEntityManager()->flush($utilisateur);
        return $utilisateur;
    }

    /**
     * @param string $username
     * @return User
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function exist($username)
    {
        $qb = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder("utilisateur")
            ->andWhere("utilisateur.username = :username")
            ->setParameter("username", $username)
        ;
        $result = $qb->getQuery()->getOneOrNullResult();
        return $result;
    }

    /**
     * @param User $utilisateur
     * @return User
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changeStatus($utilisateur)
    {
        if ($utilisateur) {
            $status = $utilisateur->getState();
            if ($status == 1 ) {
                $utilisateur->setState(0);
            }
            else {
                $utilisateur->setState(1);
            }
            $this->getEntityManager()->flush($utilisateur);
        }
        return $utilisateur;
    }
}

