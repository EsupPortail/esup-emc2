<?php

namespace Carriere\Service\Corps;

use Application\Entity\Db\Traits\HasPeriodeTrait;
use Carriere\Entity\Db\Corps;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class CorpsService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function update(Corps $corps) : Corps
    {
        try {
            $this->getEntityManager()->flush($corps);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD lors de l'enregistrement du Corps",0,$e);
        }
        return $corps;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(Corps::class)->createQueryBuilder('corps')
                ->andWhere('corps.deleted_on IS NULL');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder de [".Corps::class."]",0,$e);
        }
        return $qb;
    }

    /** @return Corps[] */
    public function getCorps(string $champ = 'libelleLong', string $ordre = 'ASC', bool $avecAgent = true, bool $avecHisto = false) : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('corps.' . $champ, $ordre)
        ;

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('corps.agentGrades', 'agentGrade')
                     ->addSelect('agent')->join('agentGrade.agent','agent')
                    ->andWhere('agent.deleted_on IS NULL')
                    ->andWhere('agentGrade.deleted_on IS NULL')
            ;
            $qb = HasPeriodeTrait::decorateWithActif($qb,'agentGrade');
        }

        if ($avecHisto === false) {
            $qb = HasPeriodeTrait::decorateWithActif($qb, 'corps');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCorp(?int $id, bool $avecAgent=true) : ?Corps
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('corps.id = :id')
            ->setParameter('id', $id)
        ;

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('corps.agentGrades', 'agentGrade')
                ->addSelect('agent')->join('agentGrade.agent','agent')
                ->andWhere('agent.deleted_on IS NULL')
            ;
        }

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Corps partagent le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    public function getRequestedCorps(AbstractActionController $controller, string $param = 'corps') : ?Corps
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCorp( (int) $id, false);
        return $result;
    }

    public function getCorpsAsOptions(string $champ = 'libelleLong', string $ordre = 'ASC', bool $avecAgent=false) : array
    {
        $corps = $this->getCorps($champ, $ordre, $avecAgent);
        $options = [];
        foreach($corps as $corp) {
            $options[$corp->getId()] = $corp->getLibelleCourt() . " - " . $corp->getLibelleLong();
        }
        return $options;
    }
}
