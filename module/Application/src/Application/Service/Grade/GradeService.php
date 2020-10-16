<?php

namespace Application\Service\Grade;

use Application\Entity\Db\Grade;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class GradeService {
    use EntityManagerAwareTrait;

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(Grade::class)->createQueryBuilder('grade');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @param bool $avecAgent
     * @return Grade[]
     */
    public function getGrades(string $champ = 'libelleLong', string $ordre ='ASC', bool $avecAgent = true)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("grade.histo IS NULL")
            ->orderBy('grade.' . $champ, $ordre)
        ;

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('grade.agentGrades', 'agentGrade')
                ->addSelect('agent')->join('agentGrade.agent','agent')
                ->andWhere('agent.delete IS NULL')
            ;
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Grade[]
     */
    public function getGradesHistorises($champ = 'libelleLong', $ordre ='ASC')
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("grade.histo IS NOT NULL")
            ->orderBy('grade.' . $champ, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getGradesAsOptions()
    {
        $grades = $this->getGrades();

        $array = [];
        foreach ($grades as $grade) {
            $array[$grade->getId()] = $grade->getLibelleCourt() . " - ". $grade->getLibelleLong();
        }
        return $array;
    }

    /**
     * @param integer $id
     * @param bool $avecAgent
     * @return Grade
     */
    public function getGrade(int $id, bool $avecAgent = true)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('grade.id = :id')
            ->setParameter('id', $id)
        ;

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('grade.agentGrades', 'agentGrade')
                ->addSelect('agent')->join('agentGrade.agent','agent')
            ;
        }

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
     * @return Grade
     */
    public function getRequestedGrade($controller, $param = "grade")
    {
        $id = $controller->params()->fromRoute($param);
        $grade = $this->getGrade($id);
        return $grade;
    }

}
