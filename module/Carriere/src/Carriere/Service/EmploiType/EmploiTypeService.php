<?php

namespace Carriere\Service\EmploiType;

use Application\Entity\Db\Traits\HasPeriodeTrait;
use Carriere\Entity\Db\EmploiType;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class EmploiTypeService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function update(EmploiType $emploitype) : EmploiType
    {
        try {
            $this->getEntityManager()->flush($emploitype);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD lors de l'enregistrement du EmploiType",0,$e);
        }
        return $emploitype;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(EmploiType::class)->createQueryBuilder('emploitype')
                ->andWhere('emploitype.deleted_on IS NULL');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder de  [".EmploiType::class."]",0,$e);
        }
        return $qb;
    }

    /** @return EmploiType[] */
    public function getEmploisTypes(string $champ = 'libelleLong', string $ordre = 'ASC', bool $avecAgent = true, bool $avecHisto = false) : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('emploitype.' . $champ, $ordre)
        ;

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('emploitype.agentGrades', 'agentGrade')
                     ->addSelect('agent')->join('agentGrade.agent','agent')
                    ->andWhere('agent.deleted_on IS NULL')
                    ->andWhere('agentGrade.deleted_on IS NULL')
            ;
            $qb = HasPeriodeTrait::decorateWithActif($qb,'agentGrade');
        }

        if ($avecHisto === false) {
            $qb = HasPeriodeTrait::decorateWithActif($qb, 'emploitype');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getEmploiType(?int $id, bool $avecAgent=true) : ?EmploiType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('emploitype.id = :id')
            ->setParameter('id', $id)
        ;

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('emploitype.agentGrades', 'agentGrade')
                ->addSelect('agent')->join('agentGrade.agent','agent')
                ->andWhere('agent.deleted_on IS NULL')
            ;
        }

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EmploiType partagent le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    public function getRequestedEmploiType(AbstractActionController $controller, string $param = 'emploi-type') : ?EmploiType
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getEmploiType( (int) $id, false);
        return $result;
    }

    public function getEmploiTypeAsOptions(string $champ = 'libelleLong', string $ordre = 'ASC', bool $avecAgent=false) : array
    {
        $emploitype = $this->getEmploisTypes($champ, $ordre, $avecAgent);
        $options = [];
        foreach($emploitype as $corp) {
            $options[$corp->getId()] = $corp->getLibelleCourt() . " - " . $corp->getLibelleLong();
        }
        return $options;
    }
}
