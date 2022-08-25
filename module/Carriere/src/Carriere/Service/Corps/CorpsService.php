<?php

namespace Carriere\Service\Corps;

use Application\Entity\Db\AgentGrade;
use Carriere\Entity\Db\Corps;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class CorpsService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Corps $corps
     * @return Corps
     */
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

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Corps::class)->createQueryBuilder('corps')
            ->andWhere('corps.deleted_on IS NULL')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @param boolean $avecAgent
     * @return Corps[]
     */
    public function getCorps(string $champ = 'libelleLong', string $ordre = 'ASC', bool $avecAgent = true) : array
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
            $qb = AgentGrade::decorateWithActif($qb,'agentGrade');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @param bool $avecAgent
     * @return Corps
     */
    public function getCorp(int $id, bool $avecAgent=true) : ?Corps
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

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Corps
     */
    public function getRequestedCorps(AbstractActionController $controller, string $param = 'corps') : ?Corps
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCorp( (int) $id, false);
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @param bool $avecAgent
     * @return array
     */
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
