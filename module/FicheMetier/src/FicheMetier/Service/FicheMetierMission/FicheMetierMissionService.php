<?php

namespace FicheMetier\Service\FicheMetierMission;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\FicheMetierMission;
use FicheMetier\Entity\Db\Mission;
use RuntimeException;

class FicheMetierMissionService
{
    use ProvidesObjectManager;

    /** Gestion des entités *******************************************************************************************/

    public function create(FicheMetierMission $ficheMetierMission): FicheMetierMission
    {
        $this->getObjectManager()->persist($ficheMetierMission);
        $this->getObjectManager()->flush($ficheMetierMission);
        return $ficheMetierMission;
    }

    public function update(FicheMetierMission $ficheMetierMission): FicheMetierMission
    {
        $this->getObjectManager()->flush($ficheMetierMission);
        return $ficheMetierMission;
    }

    public function delete(FicheMetierMission $ficheMetierMission): FicheMetierMission
    {
        $this->getObjectManager()->remove($ficheMetierMission);
        $this->getObjectManager()->flush($ficheMetierMission);
        return $ficheMetierMission;
    }

    /** Querying ******************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(FicheMetierMission::class)->createQueryBuilder('fichemetiermission')
            ->leftjoin('fichemetiermission.ficheMetier', 'ficheMetier')->addSelect('ficheMetier')
            ->leftjoin('fichemetiermission.mission', 'mission')->addSelect('mission');
        return $qb;
    }

    public function getFicheMetierMission(?int $id): ?FicheMetierMission
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fichemetier-mission.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . FicheMetierMission::class . "] partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getFicherMetierMissionByFicheMetierAndMission(FicheMetier $ficheMetier, Mission $mission): ?FicheMetierMission
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fichemetiermission.ficheMetier = :ficheMetier')->setParameter('ficheMetier', $ficheMetier)
            ->andWhere('fichemetiermission.mission = :mission')->setParameter('mission', $mission);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . FicheMetierMission::class . "] partagent les mêmes FicheMetier/Mission ", 0, $e);
        }
        return $result;
    }

    public function getFicherMetierMissionByFicheMetierAndPosition(FicheMetier $ficheMetier, int $position): ?FicheMetierMission
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fichemetiermission.ficheMetier = :ficheMetier')->setParameter('ficheMetier', $ficheMetier)
            ->andWhere('fichemetiermission.ordre = :position')->setParameter('position', $position);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . FicheMetierMission::class . "] partagent les mêmes FicheMetier/Position ", 0, $e);
        }
        return $result;
    }
}