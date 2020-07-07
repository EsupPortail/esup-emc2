<?php

namespace Application\Service\ParcoursDeFormation;

use Application\Entity\Db\Metier;
use Application\Entity\Db\ParcoursDeFormation;
use Application\Service\Metier\MetierServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ParcoursDeFormationService {
    use EntityManagerAwareTrait;
    use MetierServiceAwareTrait;

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(ParcoursDeFormation::class)->createQueryBuilder('parcours')
            ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return ParcoursDeFormation[]
     */
    public function getParcoursDeFormations($champ = 'id', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('parcours.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $type
     * @return ParcoursDeFormation[]
     */
    public function getParcoursDeFormationsByType($type)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('parcours.type = :type')
            ->setParameter('type', $type)
            ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return ParcoursDeFormation
     */
    public function getParcoursDeFormation($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('parcours.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ParcoursDeFormation partagent le même id [".$id."]", 0 , $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return ParcoursDeFormation
     */
    public function getRequestedParcoursDeFormation($controller, $param = 'parcours-de-formation')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getParcoursDeFormation($id);
        return $result;
    }

    /**
     * @param ParcoursDeFormation $parcours
     * @return Metier
     */
    public function getReference(ParcoursDeFormation $parcours)
    {
        if ($parcours->getType() === ParcoursDeFormation::TYPE_METIER) {
            $metier = $this->getMetierService()->getMetier($parcours->getId());
            return $metier;
        }
        return null;
    }
}