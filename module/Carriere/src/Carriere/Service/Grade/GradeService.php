<?php

namespace Carriere\Service\Grade;

use Carriere\Entity\Db\Grade;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Laminas\Mvc\Controller\AbstractActionController;
use Structure\Entity\Db\Structure;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class GradeService
{
    use EntityManagerAwareTrait;

    /** GESTION DES ENITIES *******************************************************************************************/
    // les grades sont importés et ne sont pas gérés dans l'application

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(Grade::class)->createQueryBuilder('grade')
                ->andWhere('grade.deleted_on IS NULL');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder de  [" . Grade::class . "]", 0, $e);
        }
        return $qb;
    }

    public function decorateWithAgent(QueryBuilder $qb): QueryBuilder
    {
        $qb = $qb
            ->join('grade.agentGrades', 'agentGrade')->addSelect('agentGrade')
            ->join('agentGrade.agent', 'agent')->addSelect('agent')
            ->andWhere('agent.deleted_on IS NULL')
            ->andWhere('agentGrade.deleted_on IS NULL');
        return $qb;
    }

    /** @return Grade[] */
    public function getGrades(string $champ = 'libelleLong', string $ordre = 'ASC', bool $avecAgent = true, bool $avecHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('grade.' . $champ, $ordre);
        if ($avecAgent) {
            $qb = $this->decorateWithAgent($qb);
            $qb = Grade::decorateWithActif($qb, 'agentGrade');
        }
        if ($avecHisto === false) {
            $qb = Grade::decorateWithActif($qb, 'grade');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getGradesAsOptions(string $champ = 'libelleLong', string $ordre = 'ASC'): array
    {
        $grades = $this->getGrades($champ, $ordre);

        $array = [];
        foreach ($grades as $grade) {
            $array[$grade->getId()] = $grade->getLibelleCourt() . " - " . $grade->getLibelleLong();
        }
        return $array;
    }

    public function getGrade(?int $id, bool $avecAgent = true): ?Grade
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('grade.id = :id')
            ->setParameter('id', $id);
        if ($avecAgent) $qb = $this->decorateWithAgent($qb);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs grades partagent le même identifiant [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedGrade(AbstractActionController $controller, string $param = "grade"): ?Grade
    {
        $id = $controller->params()->fromRoute($param);
        $grade = $this->getGrade($id);
        return $grade;
    }

    /** @return Grade[] */
    public function getGradesByTerm(mixed $term): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('lower(grade.libelleCourt) LIKE :search or lower(grade.libelleLong) LIKE :search or lower(grade.code) LIKE :search')
            ->setParameter('search', '%' . strtolower($term) . '%')
//            ->andWhere('grade.histo IS NULL')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Grade[] $grades
     * @return array
     */
    public function formatGradesJSON(array $grades): array
    {
        $result = [];
        foreach ($grades as $grade) {
            $result[] = array(
                'id' => $grade->getId(),
                'label' => $grade->getLibelleLong(),
                'extra' => "<span class='badge' style='background-color: slategray;'>" . $grade->getLibelleCourt() . "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }
}
