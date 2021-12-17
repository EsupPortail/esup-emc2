<?php

namespace Application\Service\SpecificitePoste;

use Application\Entity\Db\SpecificitePoste;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class SpecificitePosteService {
    use EntityManagerAwareTrait;

    /** GESTION ENTITE ************************************************************************************************/

    /**
     * @param SpecificitePoste $specificite
     * @return SpecificitePoste
     */
    public function create(SpecificitePoste $specificite) : SpecificitePoste
    {
        try {
            $this->getEntityManager()->persist($specificite);
            $this->getEntityManager()->flush($specificite);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la création de la spécificité du poste.", $e);
        }
        return $specificite;
    }

    /**
     * @param SpecificitePoste $specificite
     * @return SpecificitePoste
     */
    public function update(SpecificitePoste $specificite) : SpecificitePoste
    {
        try {
            $this->getEntityManager()->flush($specificite);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour de la spécificité du poste.", $e);
        }
        return $specificite;
    }

    /**
     * @param SpecificitePoste $specificite
     */
    public function delete(SpecificitePoste $specificite) : SpecificitePoste
    {
        try {
            $this->getEntityManager()->remove($specificite);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'effacement de la spécificité du poste.", $e);
        }
    }

    /** REQUETAGE ***************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(SpecificitePoste::class)->createQueryBuilder('specificite')
            ->leftJoin('specificite.activites', 'activite')->addSelect('specificite')
        ;
        return $qb;
    }

    /**
     * @param int|null $id
     * @return SpecificitePoste|null
     */
    public function getSpecificitePoste(?int $id) : ?SpecificitePoste
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('specificite.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs SpecificitePoste partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return SpecificitePoste|null
     */
    public function getRequestedSpecificitePoste(AbstractActionController $controller, string $param="specificite-poste") : ?SpecificitePoste
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getSpecificitePoste($id);
        return $result;
    }
}