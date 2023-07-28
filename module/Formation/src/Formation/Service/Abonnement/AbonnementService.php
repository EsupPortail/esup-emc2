<?php

namespace Formation\Service\Abonnement;

use Application\Entity\Db\Agent;
use DateTime;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationAbonnement;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenIndicateur\Entity\Db\Abonnement;

class AbonnementService
{
    use EntityManagerAwareTrait;

    /** Gestion des entités ***************************************************************/

    public function create(FormationAbonnement $abonnement): FormationAbonnement
    {
        try {
            $this->getEntityManager()->persist($abonnement);
            $this->getEntityManager()->flush($abonnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée", 0, $e);
        }
        return $abonnement;
    }

    public function update(FormationAbonnement $abonnement): FormationAbonnement
    {
        try {
            $this->getEntityManager()->flush($abonnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée", 0, $e);
        }
        return $abonnement;
    }

    public function historise(FormationAbonnement $abonnement): FormationAbonnement
    {
        try {
            $abonnement->historiser();
            $this->getEntityManager()->flush($abonnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée", 0, $e);
        }
        return $abonnement;
    }

    public function restore(FormationAbonnement $abonnement): FormationAbonnement
    {
        try {
            $abonnement->dehistoriser();
            $this->getEntityManager()->flush($abonnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée", 0, $e);
        }
        return $abonnement;
    }

    public function delete(FormationAbonnement $abonnement): FormationAbonnement
    {
        try {
            $this->getEntityManager()->remove($abonnement);
            $this->getEntityManager()->flush($abonnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée", 0, $e);
        }
        return $abonnement;
    }

    /** requetages  *******************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(FormationAbonnement::class)->createQueryBuilder('abonnement')
                ->join('abonnement.agent', 'agent')->addSelect('agent')
                ->join('abonnement.formation', 'formation')->addSelect('formation');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder de [".Abonnement::class."]", 0 ,$e);
        }
        return $qb;
    }

    public function getAbonnement(?int $id): ?FormationAbonnement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('abonnement.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Abonnement partagent le même id", 0, $e);
        }
        return $result;
    }

    public function getRequestedAbonnement(AbstractActionController $controller, string $param = 'abonnement'): ?FormationAbonnement
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getAbonnement($id);
        return $result;
    }

    /**
     * @param Formation $formation
     * @param bool $histo
     * @return FormationAbonnement[]
     */
    public function getAbonnementsByFormation(Formation $formation, bool $histo = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('abonnement.formation = :formation')
            ->setParameter('formation', $formation);

        if (!$histo) $qb = $qb->andWhere('abonnement.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent $agent
     * @param bool $histo
     * @return FormationAbonnement[]
     */
    public function getAbonnementsByAgent(Agent $agent, bool $histo = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('abonnement.agent = :agent')
            ->setParameter('agent', $agent);

        if (!$histo) $qb = $qb->andWhere('abonnement.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getAbonnementByAgentAndFormation(Agent $agent, Formation $formation, bool $histo = false): ?FormationAbonnement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('abonnement.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('abonnement.formation = :formation')
            ->setParameter('formation', $formation);

        if (!$histo) $qb = $qb->andWhere('abonnement.histoDestruction IS NULL');

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Abonnement partagent le même Agent/Foramtion", 0, $e);
        }
        return $result;
    }

    /** facade **********************************************************************************************/

    public function ajouterAbonnement(Agent $agent, Formation $formation): FormationAbonnement
    {
        $abonnement = new FormationAbonnement();
        $abonnement->setFormation($formation);
        $abonnement->setAgent($agent);
        $abonnement->setDateInscription(new DateTime());
        $this->create($abonnement);
        return $abonnement;
    }

    public function retirerAbonnement(Agent $agent, Formation $formation): FormationAbonnement
    {
        $abonnement = $this->getAbonnementByAgentAndFormation($agent, $formation);
        $this->historise($abonnement);
        return $abonnement;
    }

}