<?php

namespace Formation\Service\FormationInstanceJournee;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\FormationInstanceJournee;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FormationInstanceJourneeService
{
    use EntityManagerAwareTrait;

    /**  GESTION ENTITY ***********************************************************************************************/

    /**
     * @param FormationInstanceJournee $journee
     * @return FormationInstanceJournee
     */
    public function create(FormationInstanceJournee $journee) : FormationInstanceJournee
    {
        try {
            $this->getEntityManager()->persist($journee);
            $this->getEntityManager()->flush($journee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $journee;
    }

    /**
     * @param FormationInstanceJournee $journee
     * @return FormationInstanceJournee
     */
    public function update(FormationInstanceJournee $journee) : FormationInstanceJournee
    {
        try {
            $this->getEntityManager()->flush($journee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $journee;
    }

    /**
     * @param FormationInstanceJournee $journee
     * @return FormationInstanceJournee
     */
    public function historise(FormationInstanceJournee $journee) : FormationInstanceJournee
    {
        try {
            $journee->historiser();
            $this->getEntityManager()->flush($journee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $journee;
    }

    /**
     * @param FormationInstanceJournee $journee
     * @return FormationInstanceJournee
     */
    public function restore(FormationInstanceJournee $journee) : FormationInstanceJournee
    {
        try {
            $journee->dehistoriser();
            $this->getEntityManager()->flush($journee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $journee;
    }

    /**
     * @param FormationInstanceJournee $journee
     * @return FormationInstanceJournee
     */
    public function delete(FormationInstanceJournee $journee) : FormationInstanceJournee
    {
        try {
            $this->getEntityManager()->remove($journee);
            $this->getEntityManager()->flush($journee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $journee;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(FormationInstanceJournee::class)->createQueryBuilder('journee')
            ->addSelect('finstance')->join('journee.instance', 'finstance')
            ->addSelect('formation')->join('finstance.formation', 'formation');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return FormationInstanceJournee[]
     */
    public function getFormationsInstancesJournees($champ = 'id', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('journee.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return FormationInstanceJournee
     */
    public function getFormationInstanceJournee(int $id)
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
     * @return FormationInstanceJournee
     */
    public function getRequestedFormationInstanceJournee(AbstractActionController $controller, $param = 'journee')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getFormationInstanceJournee($id);
        return $result;
    }

    public function getFormationInstanceJourneeBySource(string $source, string $idSource)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('journee.source = :source')
            ->andWhere('journee.idSource = :idSource')
            ->setParameter('source', $source)
            ->setParameter('idSource', $idSource)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationInstanceJournee partagent le même idSource [" . $source . "-" . $idSource . "]", 0, $e);
        }
        return $result;

    }
}