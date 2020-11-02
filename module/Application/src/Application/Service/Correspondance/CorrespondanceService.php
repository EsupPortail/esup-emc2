<?php

namespace Application\Service\Correspondance;

use Application\Entity\Db\Correspondance;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class CorrespondanceService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENITIES *******************************************************************************************/

    // les grades sont importés et ne sont pas gérés dans l'application

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() {
        $qb = $this->getEntityManager()->getRepository(Correspondance::class)->createQueryBuilder('correspondance');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @param bool $avecAgent
     * @return Correspondance[]
     */
    public function getCorrespondances(string $champ = 'categorie', string $ordre = 'ASC', bool $avecAgent=true) {
        $qb = $this->createQueryBuilder()
            ->andWhere('correspondance.histo IS NULL')
            ->orderBy('correspondance.' . $champ, $ordre)
        ;

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('correspondance.agentGrades', 'agentGrade')
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
     * @param bool $avecAgent
     * @return array
     */
    public function getCorrespondancesAsOptions(string $champ = 'categorie', string $ordre = 'ASC', bool $avecAgent=false)
    {
        $correspondances = $this->getCorrespondances($champ, $ordre, $avecAgent);
        $options = [];
        foreach($correspondances as $correspondance) {
            $options[$correspondance->getId()] = $correspondance->getCategorie() . " - " . $correspondance->getLibelleLong();
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
            ->andWhere('correspondance.source_id = :id')
            ->setParameter('id', $id)
        ;

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('correspondance.agentGrades', 'agentGrade')
                ->addSelect('agent')->join('agentGrade.agent','agent')
                ->andWhere('agent.delete IS NULL')
            ;
        }

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Correcpondance partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Correspondance
     */
    public function getRequestedCorrespondance(AbstractActionController $controller, string $param = 'correspondance')
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getCorrespondance($id);
    }
}
