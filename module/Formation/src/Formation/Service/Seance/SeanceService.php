<?php

namespace Formation\Service\Seance;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\Seance;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;

class SeanceService
{
    use ProvidesObjectManager;

    /**  GESTION ENTITY ***********************************************************************************************/

    /**
     * @param Seance $journee
     * @return Seance
     */
    public function create(Seance $journee): Seance
    {
        $this->getObjectManager()->persist($journee);
        $this->getObjectManager()->flush($journee);
        return $journee;
    }

    /**
     * @param Seance $journee
     * @return Seance
     */
    public function update(Seance $journee): Seance
    {
        $this->getObjectManager()->flush($journee);
        return $journee;
    }

    /**
     * @param Seance $journee
     * @return Seance
     */
    public function historise(Seance $journee): Seance
    {
        $journee->historiser();
        $this->getObjectManager()->flush($journee);
        return $journee;
    }

    /**
     * @param Seance $journee
     * @return Seance
     */
    public function restore(Seance $journee): Seance
    {
        $journee->dehistoriser();
        $this->getObjectManager()->flush($journee);
        return $journee;
    }

    /**
     * @param Seance $journee
     * @return Seance
     */
    public function delete(Seance $journee): Seance
    {
        $this->getObjectManager()->remove($journee);
        $this->getObjectManager()->flush($journee);
        return $journee;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Seance::class)->createQueryBuilder('journee')
            ->addSelect('finstance')->join('journee.instance', 'finstance')
            ->addSelect('formation')->join('finstance.formation', 'formation');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Seance[]
     */
    public function getSeances(string $champ = 'id', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('journee.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Seance|null
     */
    public function getSeance(int $id): ?Seance
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('journee.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationInstanceJournee partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Seance|null
     */
    public function getRequestedSeance(AbstractActionController $controller, string $param = 'journee'): ?Seance
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getSeance($id);
        return $result;
    }

    public function getSeanceBySource(string $source, string $idSource)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('journee.source = :source')
            ->andWhere('journee.idSource = :idSource')
            ->setParameter('source', $source)
            ->setParameter('idSource', $idSource);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationInstanceJournee partagent le même idSource [" . $source . "-" . $idSource . "]", 0, $e);
        }
        return $result;

    }
}