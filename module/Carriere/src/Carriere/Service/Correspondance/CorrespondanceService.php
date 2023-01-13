<?php

namespace Carriere\Service\Correspondance;

use Carriere\Entity\Db\Correspondance;
use Carriere\Entity\Db\CorrespondanceType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class CorrespondanceService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENITIES *******************************************************************************************/

    // les grades sont importés et ne sont pas gérés dans l'application

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() {
        $qb = $this->getEntityManager()->getRepository(Correspondance::class)->createQueryBuilder('correspondance')
            ->leftJoin('correspondance.type','ctype')->addSelect('ctype')
            ->andWhere('correspondance.deleted_on IS NULL')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @param bool $avecAgent
     * @return Correspondance[]
     */
    public function getCorrespondances(string $champ = 'categorie', string $ordre = 'ASC', bool $avecAgent=true) : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('correspondance.' . $champ, $ordre)
        ;

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('correspondance.agentGrades', 'agentGrade')
                ->addSelect('agent')->join('agentGrade.agent','agent')
                ->andWhere('agent.deleted_on IS NULL')
                ->andWhere('agentGrade.deleted_on IS NULL')

            ;
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @param bool $avecAgent
     * @return array
     */
    public function getCorrespondancesAsOptions(string $champ = 'categorie', string $ordre = 'ASC', bool $avecAgent=false)
    {
        $correspondances = $this->getCorrespondances($champ, $ordre, $avecAgent);
        $options = [];
        foreach($correspondances as $correspondance) {
            $options[$correspondance->getId()] = (($correspondance->getType())?$correspondance->getType()->getLibelleCourt():""). " ". $correspondance->getLibelleCourt() . " - " . $correspondance->getLibelleLong();
        }
        return $options;
    }

    /**
     * @param int $id
     * @param bool $avecAgent
     * @return Correspondance
     */
    public function getCorrespondance(int $id, bool $avecAgent = true)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('correspondance.id = :id')
            ->setParameter('id', $id)
        ;

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('correspondance.agentGrades', 'agentGrade')
                ->addSelect('agent')->join('agentGrade.agent','agent')
                ->andWhere('agent.deleted_on IS NULL')
            ;
        }

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Correcpondance partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedCorrespondance(AbstractActionController $controller, string $param = 'correspondance') : ?Correspondance
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getCorrespondance($id);
    }

    /**
     * @param CorrespondanceType|null $type
     * @return Correspondance[]
     */
    public function getCorrespondancesByType(?CorrespondanceType $type) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('correspondance.type = :type')->setParameter('type', $type)
            ->orderBy('correspondance.libelleCourt', 'ASC')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}
