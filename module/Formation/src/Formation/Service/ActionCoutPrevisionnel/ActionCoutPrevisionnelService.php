<?php

namespace Formation\Service\ActionCoutPrevisionnel;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\ActionCoutPrevisionnel;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\PlanDeFormation;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class ActionCoutPrevisionnelService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES ****************************************************************/

    public function create(ActionCoutPrevisionnel $actionCoutPrevisionnel): ActionCoutPrevisionnel
    {
        $this->getObjectManager()->persist($actionCoutPrevisionnel);
        $this->getObjectManager()->flush($actionCoutPrevisionnel);
        return $actionCoutPrevisionnel;
    }

    public function update(ActionCoutPrevisionnel $actionCoutPrevisionnel): ActionCoutPrevisionnel
    {
        $this->getObjectManager()->flush($actionCoutPrevisionnel);
        return $actionCoutPrevisionnel;
    }

    public function historise(ActionCoutPrevisionnel $actionCoutPrevisionnel): ActionCoutPrevisionnel
    {
        $actionCoutPrevisionnel->historiser();
        $this->getObjectManager()->flush($actionCoutPrevisionnel);
        return $actionCoutPrevisionnel;
    }

    public function restore(ActionCoutPrevisionnel $actionCoutPrevisionnel): ActionCoutPrevisionnel
    {
        $actionCoutPrevisionnel->dehistoriser();
        $this->getObjectManager()->flush($actionCoutPrevisionnel);
        return $actionCoutPrevisionnel;
    }

    public function delete(ActionCoutPrevisionnel $actionCoutPrevisionnel): ActionCoutPrevisionnel
    {
        $this->getObjectManager()->remove($actionCoutPrevisionnel);
        $this->getObjectManager()->flush($actionCoutPrevisionnel);
        return $actionCoutPrevisionnel;
    }

    /** REQUETAGE **************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(ActionCoutPrevisionnel::class)->createQueryBuilder("actioncoutprevisionnel")
            ->leftjoin('actioncoutprevisionnel.action', 'action')->addSelect('action')
            ->leftjoin('actioncoutprevisionnel.plan', 'plan')->addSelect('plan');

        return $qb;
    }

    public function getActionCoutPrevisionnel(?int $id): ActionCoutPrevisionnel
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('actioncoutprevisionnel.id = :id')->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . ActionCoutPrevisionnel::class . "] partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedActionCoutPrevisionnel(AbstractActionController $controller, string $param = 'action-cout-previsionnel'): ?ActionCoutPrevisionnel
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getActionCoutPrevisionnel($id);
    }

    /** @return ActionCoutPrevisionnel[] */
    public function getActionsCoutsPrevisionnels(string $champ = 'id', string $ordre = 'ASC', bool $histo = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('actioncoutprevisionnel.' . $champ, $ordre);
        if (!$histo) $qb = $qb->andWhere('actioncoutprevisionnel.histoDestruction IS NULL');

        return $qb->getQuery()->getResult();
    }

    /** @return ActionCoutPrevisionnel[] */
    public function getActionsCoutsPrevisionnelsByAction(Formation $action, bool $histo = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('actioncoutprevisionnel.action = :action')->setParameter('action', $action)
            ->orderBy('actioncoutprevisionnel.id', 'ASC');
        if (!$histo) $qb = $qb->andWhere('actioncoutprevisionnel.histoDestruction IS NULL');

        return $qb->getQuery()->getResult();
    }

    /** @return ActionCoutPrevisionnel[] */
    public function getActionsCoutsPrevisionnelsByPlan(PlanDeFormation $plan, bool $histo = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('actioncoutprevisionnel.plan = :plan')->setParameter('plan', $plan)
            ->orderBy('actioncoutprevisionnel.id', 'ASC');
        if (!$histo) $qb = $qb->andWhere('actioncoutprevisionnel.histoDestruction IS NULL');

        return $qb->getQuery()->getResult();
    }

    public function getActionCoutPrevisionnelByActionAndPlan(Formation $action, PlanDeFormation $plan): ?ActionCoutPrevisionnel
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('actioncoutprevisionnel.action = :action')->setParameter('action', $action)
            ->andWhere('actioncoutprevisionnel.plan = :plan')->setParameter('plan', $plan)
            ->andWhere('actioncoutprevisionnel.histoDestruction IS NULL')
            ->orderBy('actioncoutprevisionnel.id', 'ASC');

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . ActionCoutPrevisionnel::class . "] partagent la même Action/Plan [" . $action->getId() . "/" . $plan->getId() . "]", 0, $e);
        }
        return $result;
    }

    /** @return ActionCoutPrevisionnel[] */
    public function getActionsCoutsPrevisionnelsWithFiltre(array $params): array
    {
        $actionId = $params['action-de-formation']??null;
        $planId = $params['plan-de-formation']??null;
        $histo = $params['histo']??null;

        $qb = $this->createQueryBuilder();
        if ($actionId) $qb = $qb->andWhere('action.id = :actionId')->setParameter('actionId', $actionId);
        if ($planId) $qb = $qb->andWhere('plan.id = :planId')->setParameter('planId', $planId);
        if ($histo == "oui") $qb = $qb->andWhere('actioncoutprevisionnel.histoDestruction IS NOT NULL');
        if ($histo == "non") $qb = $qb->andWhere('actioncoutprevisionnel.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE *****************************************************************************/
}