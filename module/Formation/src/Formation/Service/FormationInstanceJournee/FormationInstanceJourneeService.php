<?php

namespace Formation\Service\FormationInstanceJournee;

use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\FormationInstanceJournee;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class FormationInstanceJourneeService
{
    use GestionEntiteHistorisationTrait;

    /**  GESTION ENTITY ***********************************************************************************************/

    /**
     * @param FormationInstanceJournee $journee
     * @return FormationInstanceJournee
     */
    public function create(FormationInstanceJournee $journee)
    {
        $this->createFromTrait($journee);
        return $journee;
    }

    /**
     * @param FormationInstanceJournee $journee
     * @return FormationInstanceJournee
     */
    public function update(FormationInstanceJournee $journee)
    {
        $this->updateFromTrait($journee);
        return $journee;
    }

    /**
     * @param FormationInstanceJournee $journee
     * @return FormationInstanceJournee
     */
    public function historise(FormationInstanceJournee $journee)
    {
        $this->historiserFromTrait($journee);
        return $journee;
    }

    /**
     * @param FormationInstanceJournee $journee
     * @return FormationInstanceJournee
     */
    public function restore(FormationInstanceJournee $journee)
    {
        $this->restoreFromTrait($journee);
        return $journee;
    }

    /**
     * @param FormationInstanceJournee $journee
     * @return FormationInstanceJournee
     */
    public function delete(FormationInstanceJournee $journee)
    {
        $this->deleteFromTrait($journee);
        return $journee;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
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
}