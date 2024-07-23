<?php

namespace Formation\Service\PlanDeFormation;

use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\PlanDeFormation;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class PlanDeFormationService
{
    use ProvidesObjectManager;
    use FormationServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(PlanDeFormation $plan): PlanDeFormation
    {
        $this->getObjectManager()->persist($plan);
        $this->getObjectManager()->flush($plan);
        return $plan;
    }

    public function update(PlanDeFormation $plan): PlanDeFormation
    {
        $this->getObjectManager()->flush($plan);
        return $plan;
    }

//    public function historise(PlanDeFormation $plan) : PlanDeFormation
//    {
//        $plan->historiser();
//        try {
//            $this->getObjectManager()->flush($plan);
//        } catch (ORMException $e) {
//            throw new RuntimeException("Un problème est survenu en BD",0,$e);
//        }
//        return $plan;
//    }

//    public function restore(PlanDeFormation $plan) : PlanDeFormation
//    {
//        $plan->dehistoriser();
//        try {
//            $this->getObjectManager()->flush($plan);
//        } catch (ORMException $e) {
//            throw new RuntimeException("Un problème est survenu en BD",0,$e);
//        }
//        return $plan;
//    }

    public function delete(PlanDeFormation $plan): PlanDeFormation
    {
        $this->getObjectManager()->remove($plan);
        $this->getObjectManager()->flush($plan);
        return $plan;
    }

    /** REQUETAGE ********************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(PlanDeFormation::class)->createQueryBuilder('plan')
            ->leftjoin('plan.formations', 'formation')->addSelect('formation')
            ->leftjoin('formation.groupe', 'groupe')->addSelect('groupe')
            ->andWhere('formation.histoDestruction IS NULL');
        return $qb;
    }

    /** @return PlanDeFormation[] */
    public function getPlansDeFormation(string $champ = 'dateDebut', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('plan.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getPlanDeFormation(?int $id): ?PlanDeFormation
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('plan.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs PlanDeFormation partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedPlanDeFormation(AbstractActionController $controller, string $param = 'plan-de-formation'): ?PlanDeFormation
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getPlanDeFormation($id);
        return $result;
    }

    /** @return PlanDeFormation[] */
    public function getPlansDeFormationActifs(?DateTime $date = null): array
    {
        if ($date === null) $date = new DateTime();

        $qb = $this->createQueryBuilder()
            ->andWhere('plan.dateDebut IS NULL or plan.dateDebut <= :date')
            ->andWhere('plan.dateFin IS NULL or plan.dateFin >= :date')
            ->setParameter('date', $date)
            ->andWhere('plan.histoDestruction IS NULL');
        return $qb->getQuery()->getResult();
    }

    public function getPlansDeFormationAsOption(string $champ = 'dateDebut', string $ordre = 'ASC'): array
    {
        $plans = $this->getPlansDeFormation($champ, $ordre);
        $options = [];
        foreach ($plans as $plan) {
            $options[$plan->getId()] = $plan->getLibelle() . " (" . ")";
        }
        return $options;
    }

    /** FACADE ********************************************************************************************************/

    public function transferer(PlanDeFormation $toRecopy, PlanDeFormation $planDeFormation): void
    {
        foreach ($toRecopy->getFormations() as $formation) {
            if (!$formation->hasPlanDeFormation($planDeFormation)) {
                $formation->addPlanDeForamtion($planDeFormation);
                $this->getFormationService()->update($formation);
            }
        }
    }

    public function vider(?PlanDeFormation $plan): void
    {
        $formations = $plan->getFormations();
        foreach ($formations as $formation) {
            $formation->removePlanDeFormation($plan);
            $this->getFormationService()->update($formation);
        }
    }
}