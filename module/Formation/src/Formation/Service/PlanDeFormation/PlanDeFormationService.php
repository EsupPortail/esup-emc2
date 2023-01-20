<?php

namespace Formation\Service\PlanDeFormation;

use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\PlanDeFormation;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class PlanDeFormationService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(PlanDeFormation $plan) : PlanDeFormation
    {
        try {
            $this->getEntityManager()->persist($plan);
            $this->getEntityManager()->flush($plan);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD",0,$e);
        }
        return $plan;
    }

    public function update(PlanDeFormation $plan) : PlanDeFormation
    {
        try {
            $this->getEntityManager()->flush($plan);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD",0,$e);
        }
        return $plan;
    }

//    public function historise(PlanDeFormation $plan) : PlanDeFormation
//    {
//        $plan->historiser();
//        try {
//            $this->getEntityManager()->flush($plan);
//        } catch (ORMException $e) {
//            throw new RuntimeException("Un problème est survenu en BD",0,$e);
//        }
//        return $plan;
//    }

//    public function restore(PlanDeFormation $plan) : PlanDeFormation
//    {
//        $plan->dehistoriser();
//        try {
//            $this->getEntityManager()->flush($plan);
//        } catch (ORMException $e) {
//            throw new RuntimeException("Un problème est survenu en BD",0,$e);
//        }
//        return $plan;
//    }

    public function delete(PlanDeFormation $plan) : PlanDeFormation
    {
        try {
            $this->getEntityManager()->remove($plan);
            $this->getEntityManager()->flush($plan);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD",0,$e);
        }
        return $plan;
    }

    /** REQUETAGE ********************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(PlanDeFormation::class)->createQueryBuilder('plan')
            ->leftjoin('plan.formations', 'formation')->addSelect('formation')
            ->leftjoin('formation.groupe', 'groupe')->addSelect('groupe')
            ->andWhere('formation.histoDestruction IS NULL');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return PlanDeFormation[]
     */
    public function getPlansDeFormation(string $champ='annee', string $ordre='ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('plan.'.$champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getPlanDeFormation(?int $id) : ?PlanDeFormation
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('plan.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs PlanDeFormation partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedPlanDeFormation(AbstractActionController $controller, string $param='plan-de-formation') : ?PlanDeFormation
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getPlanDeFormation($id);
        return $result;
    }

    public function getPlanDeFormationByAnnee(?string $annee = null) : ?PlanDeFormation
    {
        if ($annee === null) {
            $annee = Formation::getAnnee();
            $annee = $annee ."/".($annee+1);
        }

        $qb = $this->createQueryBuilder()
            ->andWhere('plan.annee = :annee')->setParameter('annee', $annee);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs plan de formation partagent la même année [".$annee."]", 0 , $e);
        }
        return $result;
    }
}