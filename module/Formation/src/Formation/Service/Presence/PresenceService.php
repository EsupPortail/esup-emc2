<?php

namespace Formation\Service\Presence;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Entity\Db\Seance;
use Formation\Entity\Db\Presence;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class PresenceService
{
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function create(Presence $presence) : Presence
    {
        try {
            $this->getEntityManager()->persist($presence);
            $this->getEntityManager()->flush($presence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $presence;
    }

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function update(Presence $presence) : Presence
    {
        try {
            $this->getEntityManager()->flush($presence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $presence;
    }

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function historise(Presence $presence) : Presence
    {
        try {
            $presence->historiser();
            $this->getEntityManager()->flush($presence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $presence;
    }

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function restore(Presence $presence) : Presence
    {
        try {
            $presence->dehistoriser();
            $this->getEntityManager()->flush($presence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $presence;
    }

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function deleteFromTrait(Presence $presence) : Presence
    {
        try {
            $this->getEntityManager()->remove($presence);
            $this->getEntityManager()->flush($presence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $presence;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Presence::class)->createQueryBuilder('presence')
            ->addSelect('journee')->join('presence.journee', 'journee')
            ->addSelect('finstance')->join('journee.instance', 'finstance')
            ->addSelect('formation')->join('finstance.formation', 'formation')
            ->addSelect('inscrit')->join('presence.inscrit', 'inscrit')
            ->addSelect('agent')->join('inscrit.agent', 'agent');

        return $qb;
    }

    /**
     * @param integer|null $id
     * @return Presence|null
     */
    public function getPresence(?int $id) : ?Presence
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('presence.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Presence partagent le même id [" . $id . "].");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Presence|null
     */
    public function getRequestedPresence(AbstractActionController $controller, string $param = 'presence') : ?Presence
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getPresence($id);
    }

    /**
     * @param Seance $journee
     * @param FormationInstanceInscrit $inscrit
     * @return Presence|null
     */
    public function getPresenceByJourneeAndInscrit(Seance $journee, FormationInstanceInscrit $inscrit) : ?Presence
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('presence.journee = :journee')
            ->andWhere('presence.inscrit = :inscrit')
            ->setParameter('journee', $journee)
            ->setParameter('inscrit', $inscrit)
            ->andWhere('presence.histoDestruction IS NULL');

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Presence (non historisées) partagent la même journée [" . $journee->getId() . "] et le même inscrit [" . $inscrit->getId() . "]");
        }
        return $result;
    }

    /**
     * @param FormationInstance $instance
     * @return Presence[]
     */
    public function getPresenceByInstance(FormationInstance $instance) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('journee.instance = :instance')
            ->setParameter('instance', $instance);

        return $qb->getQuery()->getResult();

    }

    /**
     * @return Presence[]
     */
    public function getPresences() : array
    {
        $qb = $this->getEntityManager()->getRepository(Presence::class)->createQueryBuilder('presence');
        $result = $qb->getQuery()->getResult();
        return $result;
    }


}