<?php

namespace Formation\Service\StagiaireExterne;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\StagiaireExterne;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenUtilisateur\Entity\Db\User;

class StagiaireExterneService {
    use ProvidesObjectManager;
    
    /** Gestion des entités ******************************************************************/

    public function create(StagiaireExterne $stagiaireExterne): StagiaireExterne
    {
        $this->getObjectManager()->persist($stagiaireExterne);
        $this->getObjectManager()->flush($stagiaireExterne);
        return $stagiaireExterne;
    }
    
    public function update(StagiaireExterne $stagiaireExterne): StagiaireExterne
    {
        $this->getObjectManager()->flush($stagiaireExterne);
        return $stagiaireExterne;
    }

    public function historise(StagiaireExterne $stagiaireExterne): StagiaireExterne
    {
        $stagiaireExterne->historiser();
        $this->getObjectManager()->flush($stagiaireExterne);
        return $stagiaireExterne;
    }

    public function restore(StagiaireExterne $stagiaireExterne): StagiaireExterne
    {
        $stagiaireExterne->dehistoriser();
        $this->getObjectManager()->flush($stagiaireExterne);
        return $stagiaireExterne;
    }

    public function delete(StagiaireExterne $stagiaireExterne): StagiaireExterne
    {
        $this->getObjectManager()->remove($stagiaireExterne);
        $this->getObjectManager()->flush($stagiaireExterne);
        return $stagiaireExterne;
    }
    
    /** querying ******************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(StagiaireExterne::class)->createQueryBuilder('stagiaireexterne')
        ;
        return $qb;
    }

    public function getStagiaireExterne(?int $id): ?StagiaireExterne
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('stagiaireexterne.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".StagiaireExterne::class."] partagent le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    public function getRequestedStagiaireExterne(AbstractActionController $controller, string $param='stagiaire-externe'): ?StagiaireExterne
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getStagiaireExterne($id);
    }

    /** @return StagiaireExterne[] */
    public function getStagiaireExternes(string $champ='nom', string $ordre='ASC', bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('stagiaireexterne.'.$champ, $ordre);
        if (!$withHisto) $qb = $qb->andWhere('stagiaireexterne.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getStagiaireExterneByLogin(string $login, bool $withHisto = false) : ?StagiaireExterne
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('stagiaireexterne.login = :login')->setParameter('login', $login);
        if (!$withHisto) $qb = $qb->andWhere('stagiaireexterne.histoDestruction IS NULL');
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".StagiaireExterne::class."] le même login [".$login."]", 0, $e);
        }
        return $result;
    }

    /** Gestion pour les rôles automatiques */

    public function getUsersInStagiaireExterne() : array
    {
        $qb = $this->getObjectManager()->getRepository(StagiaireExterne::class)->createQueryBuilder('stagiaireexterne')
            ->join('stagiaireexterne.utilisateur', 'utilisateur')
            ->orderBy('stagiaireexterne.nom, stagiaireexterne.prenom', 'ASC')
        ;
        $result = $qb->getQuery()->getResult();

        $users = [];
        /** @var StagiaireExterne $item */
        foreach ($result as $item) {
            $users[] = $item->getUtilisateur();
        }
        return $users;
    }

    public function getStagiaireExterneByUser(?User $user) : ?StagiaireExterne
    {
        if ($user === null) return null;

        //en utilisant l'id
        $qb = $this->getObjectManager()->getRepository(StagiaireExterne::class)->createQueryBuilder('stagiaireexterne')
            ->andWhere('stagiaireexterne.utilisateur = :user')
            ->setParameter('user', $user)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".StagiaireExterne::class."] liés au même User [Id:".$user->getId()." Username:".$user->getUsername()."]", $e);
        }
        if ($result !== null) return $result;

        //en utilisant l'username si echec
        $qb = $this->getObjectManager()->getRepository(StagiaireExterne::class)->createQueryBuilder('stagiaireexterne')
            ->andWhere('stagiaireexterne.login = :username')
            ->andWhere('stagiaireexterne.histoDestruction IS NULL')
            ->setParameter('username', $user->getUsername())
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".StagiaireExterne::class."] liés au même Username [".$user->getUsername()."]", $e);
        }
        return $result;
    }

    /** Facade ********************************************************************************************************/

}