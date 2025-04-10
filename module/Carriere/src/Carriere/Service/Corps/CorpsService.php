<?php

namespace Carriere\Service\Corps;

use Agent\Entity\Db\AgentGrade;
use Carriere\Entity\Db\Corps;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class CorpsService
{

    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function update(Corps $corps): Corps
    {
        $this->getObjectManager()->flush($corps);
        return $corps;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Corps::class)->createQueryBuilder('corps')
            ->andWhere('corps.deletedOn IS NULL');
        return $qb;
    }

    /** @return Corps[] */
    public function getCorps(string $champ = 'libelleLong', string $ordre = 'ASC', bool $avecAgent = true, bool $avecHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('corps.' . $champ, $ordre);

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('corps.agentGrades', 'agentGrade')
                ->addSelect('agent')->join('agentGrade.agent', 'agent')
                ->andWhere('agent.deletedOn IS NULL')
                ->andWhere('agentGrade.deletedOn IS NULL');
            $qb = AgentGrade::decorateWithActif($qb, 'agentGrade');
        }

        if ($avecHisto === false) {
            $qb = Corps::decorateWithActif($qb, 'corps');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCorp(?int $id, bool $avecAgent = true): ?Corps
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('corps.id = :id')
            ->setParameter('id', $id);

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('corps.agentGrades', 'agentGrade')
                ->addSelect('agent')->join('agentGrade.agent', 'agent')
                ->andWhere('agent.deletedOn IS NULL');
        }

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Corps partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedCorps(AbstractActionController $controller, string $param = 'corps'): ?Corps
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCorp((int)$id, false);
        return $result;
    }

    public function getCorpsAsOptions(string $champ = 'libelleLong', string $ordre = 'ASC', bool $avecAgent = false): array
    {
        $corps = $this->getCorps($champ, $ordre, $avecAgent);
        $options = [];
        foreach ($corps as $corp) {
            $options[$corp->getId()] = $corp->getLibelleCourt() . " - " . $corp->getLibelleLong();
        }
        return $options;
    }

    /** @return Corps[] */
    public function getCorpsByTerm(string $term): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('lower(corps.libelleCourt) LIKE :search or lower(corps.libelleLong) LIKE :search or lower(corps.code) LIKE :search')
            ->setParameter('search', '%' . strtolower($term) . '%')
//            ->andWhere('corps.histo IS NULL')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Corps[] $corps
     * @return array
     */
    public function formatCorpsJSON(array $corps): array
    {
        $result = [];
        foreach ($corps as $corp) {
            $result[] = array(
                'id' => $corp->getId(),
                'label' => $corp->getLibelleLong(),
                'extra' => "<span class='badge' style='background-color: slategray;'>" . $corp->getLibelleCourt() . "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }
}
