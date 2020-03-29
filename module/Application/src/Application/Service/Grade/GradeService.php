<?php

namespace Application\Service\Grade;

use Application\Entity\Db\Grade;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class GradeService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES - remonté d'octopus ***********************************************************************/

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(Grade::class)->createQueryBuilder('grade');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Grade[]
     */
    public function getGrades($champ = 'libelleLong', $ordre ='ASC')
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("grade.histo = :nope")
            ->setParameter('nope', 'O')
            ->orderBy('grade.' . $champ, $ordre)
        ;
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
            ->andWhere("grade.histo != :nope")
            ->setParameter('nope', 'O')
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
     * @return Grade
     */
    public function getGrade($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('grade.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs grades partagent le même identifiant [".$id."]");
        }
        return $result;
    }

}
