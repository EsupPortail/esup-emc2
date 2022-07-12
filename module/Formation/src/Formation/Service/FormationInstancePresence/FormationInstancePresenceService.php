<?php

namespace Formation\Service\FormationInstancePresence;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Entity\Db\FormationInstanceJournee;
use Formation\Entity\Db\FormationInstancePresence;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class FormationInstancePresenceService
{
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FormationInstancePresence $presence
     * @return FormationInstancePresence
     */
    public function create(FormationInstancePresence $presence) : FormationInstancePresence
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
     * @param FormationInstancePresence $presence
     * @return FormationInstancePresence
     */
    public function update(FormationInstancePresence $presence) : FormationInstancePresence
    {
        try {
            $this->getEntityManager()->flush($presence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $presence;
    }

    /**
     * @param FormationInstancePresence $presence
     * @return FormationInstancePresence
     */
    public function historise(FormationInstancePresence $presence) : FormationInstancePresence
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
     * @param FormationInstancePresence $presence
     * @return FormationInstancePresence
     */
    public function restore(FormationInstancePresence $presence) : FormationInstancePresence
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
     * @param FormationInstancePresence $presence
     * @return FormationInstancePresence
     */
    public function deleteFromTrait(FormationInstancePresence $presence) : FormationInstancePresence
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
        $qb = $this->getEntityManager()->getRepository(FormationInstancePresence::class)->createQueryBuilder('presence')
            ->addSelect('journee')->join('presence.journee', 'journee')
            ->addSelect('finstance')->join('journee.instance', 'finstance')
            ->addSelect('formation')->join('finstance.formation', 'formation')
            ->addSelect('inscrit')->join('presence.inscrit', 'inscrit')
            ->addSelect('agent')->join('inscrit.agent', 'agent');

        return $qb;
    }

    /**
     * @param integer|null $id
     * @return FormationInstancePresence
     */
    public function getFormationInstancePresence(?int $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('presence.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationInstancePresence partagent le même id [" . $id . "].");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FormationInstancePresence
     */
    public function getRequestedFormationInstancePresence(AbstractActionController $controller, $param = 'presence')
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getFormationInstancePresence($id);
    }

    /**
     * @param FormationInstanceJournee $journee
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstancePresence
     */
    public function getFormationInstancePresenceByJourneeAndInscrit(FormationInstanceJournee $journee, FormationInstanceInscrit $inscrit)
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
            throw new RuntimeException("Plusieurs FormationInstancePresence (non historisées) partagent la même journée [" . $journee->getId() . "] et le même inscrit [" . $inscrit->getId() . "]");
        }
        return $result;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstancePresence[]
     */
    public function getFormationInstancePresenceByInstance(FormationInstance $instance)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('journee.instance = :instance')
            ->setParameter('instance', $instance);

        return $qb->getQuery()->getResult();

    }

    /**
     * @return FormationInstancePresence[]
     */
    public function getFormationsInstancesPresences()
    {
        $qb = $this->getEntityManager()->getRepository(FormationInstancePresence::class)->createQueryBuilder('presence');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FONCTIONS UTILITAIRES ******************************************************************************************/

}