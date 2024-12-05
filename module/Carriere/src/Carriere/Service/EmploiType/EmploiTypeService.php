<?php

namespace Carriere\Service\EmploiType;

use Application\Entity\Db\AgentGrade;
use Carriere\Entity\Db\EmploiType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;

class EmploiTypeService
{

    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function update(EmploiType $emploitype): EmploiType
    {
        $this->getObjectManager()->flush($emploitype);
        return $emploitype;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(EmploiType::class)->createQueryBuilder('emploitype')
            ->andWhere('emploitype.deleted_on IS NULL');
        return $qb;
    }

    /** @return EmploiType[] */
    public function getEmploisTypes(string $champ = 'libelleLong', string $ordre = 'ASC', bool $avecAgent = true, bool $avecHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('emploitype.' . $champ, $ordre);

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('emploitype.agentGrades', 'agentGrade')
                ->addSelect('agent')->join('agentGrade.agent', 'agent')
                ->andWhere('agent.deletedOn IS NULL')
                ->andWhere('agentGrade.deleted_on IS NULL');
            $qb = AgentGrade::decorateWithActif($qb, 'agentGrade');
        }

        if ($avecHisto === false) {
            $qb = EmploiType::decorateWithActif($qb, 'emploitype');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getEmploiType(?int $id, bool $avecAgent = true): ?EmploiType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('emploitype.id = :id')
            ->setParameter('id', $id);

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('emploitype.agentGrades', 'agentGrade')
                ->addSelect('agent')->join('agentGrade.agent', 'agent')
                ->andWhere('agent.deletedOn IS NULL');
        }

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EmploiType partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedEmploiType(AbstractActionController $controller, string $param = 'emploi-type'): ?EmploiType
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getEmploiType((int)$id, false);
        return $result;
    }

}
