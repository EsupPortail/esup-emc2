<?php

namespace Application\Service\Corps;

use Application\Entity\Db\Corps;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class CorpsService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Corps $corps
     * @return Corps
     */
    public function update(Corps $corps)
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
    public function createQueryBuilder() {
        $qb = $this->getEntityManager()->getRepository(Corps::class)->createQueryBuilder('corps');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Corps[]
     */
    public function getCorps($champ = 'libelleLong', $ordre = 'ASC') {
        $qb = $this->createQueryBuilder()
            ->andWhere('corps.histo IS NULL')
            ->orderBy('corps.' . $champ, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Corps[]
     */
    public function getCorpsHistorises($champ = 'libelleLong', $ordre = 'ASC') {
        $qb = $this->createQueryBuilder()
            ->andWhere('corps.histo IS NOT NULL')
            ->orderBy('corps.' . $champ, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Corps
     */
    public function getCorp($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('corps.id = :id')
            ->setParameter('id', $id)
        ;
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
    public function getRequestedCorps($controller, $param = 'corps')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCorp($id);
        return $result;
    }
}
