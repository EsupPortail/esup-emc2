<?php

namespace FicheMetier\Service\FicheMetierMission;

use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\FicheMetierMission;
use FicheMetier\Entity\Db\Mission;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class FicheMetierMissionService
{
    use EntityManagerAwareTrait;

    /** Gestion des entités *******************************************************************************************/

    public function create(FicheMetierMission $ficheMetierMission): FicheMetierMission
    {
        try {
            $this->getEntityManager()->persist($ficheMetierMission);
            $this->getEntityManager()->flush($ficheMetierMission);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée", 0 , $e);
        }
        return $ficheMetierMission;
    }

    public function update(FicheMetierMission $ficheMetierMission): FicheMetierMission
    {
        try {
            $this->getEntityManager()->flush($ficheMetierMission);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée", 0 , $e);
        }
        return $ficheMetierMission;
    }

    public function delete(FicheMetierMission $ficheMetierMission): FicheMetierMission
    {
        try {
            $this->getEntityManager()->remove($ficheMetierMission);
            $this->getEntityManager()->flush($ficheMetierMission);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée", 0 , $e);
        }
        return $ficheMetierMission;
    }

    /** Querying ******************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(FicheMetierMission::class)->createQueryBuilder('fichemetiermission')
                ->leftjoin('fichemetiermission.ficheMetier', 'ficheMetier')->addSelect('ficheMetier')
                ->leftjoin('fichemetiermission.mission', 'mission')->addSelect('mission')
            ;
        } catch (NotSupported $e) {
            throw new RuntimeException("Un pribleme est survenu lors de la création du QueryBuilder de [".FicheMetierMission::class."]",0,$e);
        }
        return $qb;
    }

    public function getFicheMetierMission(?int $id) : ?FicheMetierMission
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fichemetier-mission.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".FicheMetierMission::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getFicherMetierMissionByFicheMetierAndMission(FicheMetier $ficheMetier, Mission $mission) : ?FicheMetierMission
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fichemetiermission.ficheMetier = :ficheMetier')->setParameter('ficheMetier', $ficheMetier)
            ->andWhere('fichemetiermission.mission = :mission')->setParameter('mission', $mission);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".FicheMetierMission::class."] partagent les mêmes FicheMetier/Mission ",0,$e);
        }
        return $result;
    }

    public function getFicherMetierMissionByFicheMetierAndPosition(FicheMetier $ficheMetier, int $position) : ?FicheMetierMission
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fichemetiermission.ficheMetier = :ficheMetier')->setParameter('ficheMetier', $ficheMetier)
            ->andWhere('fichemetiermission.ordre = :position')->setParameter('position', $position);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".FicheMetierMission::class."] partagent les mêmes FicheMetier/Position ",0,$e);
        }
        return $result;
    }
}