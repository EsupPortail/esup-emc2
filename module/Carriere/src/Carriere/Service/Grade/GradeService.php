<?php

namespace Carriere\Service\Grade;

use Application\Entity\Db\AgentGrade;
use Carriere\Entity\Db\Grade;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class GradeService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENITIES *******************************************************************************************/
    // les grades sont importés et ne sont pas gérés dans l'application

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Grade::class)->createQueryBuilder('grade')
            ->andWhere('grade.deleted_on IS NULL')
        ;
        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @return QueryBuilder
     */
    public function decorateWithAgent(QueryBuilder $qb) : QueryBuilder
    {
        $qb = $qb
            ->join('grade.agentGrades', 'agentGrade')->addSelect('agentGrade')
            ->join('agentGrade.agent','agent')->addSelect('agent')
            ->andWhere('agent.deleted_on IS NULL')
            ->andWhere('agentGrade.deleted_on IS NULL')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @param bool $avecAgent
     * @return Grade[]
     */
    public function getGrades(string $champ = 'libelleLong', string $ordre ='ASC', bool $avecAgent = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("grade.histo IS NULL")
            ->orderBy('grade.' . $champ, $ordre)
        ;
        if ($avecAgent) {
            $qb = $this->decorateWithAgent($qb);
            $qb = AgentGrade::decorateWithActif($qb,'agentGrade');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getGradesAsOptions(string $champ = 'libelleLong', string $ordre ='ASC') : array
    {
        $grades = $this->getGrades($champ, $ordre);

        $array = [];
        foreach ($grades as $grade) {
            $array[$grade->getId()] = $grade->getLibelleCourt() . " - ". $grade->getLibelleLong();
        }
        return $array;
    }

    /**
     * @param integer $id
     * @param bool $avecAgent
     * @return Grade|null
     */
    public function getGrade(int $id, bool $avecAgent = true) : ?Grade
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('grade.id = :id')
            ->setParameter('id', $id)
        ;
        if ($avecAgent) $qb = $this->decorateWithAgent($qb);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs grades partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Grade|null
     */
    public function getRequestedGrade(AbstractActionController $controller, string $param = "grade") : ?Grade
    {
        $id = $controller->params()->fromRoute($param);
        $grade = $this->getGrade($id);
        return $grade;
    }

}
