<?php

namespace Application\Service\SpecificiteActivite;

use Application\Entity\Db\SpecificiteActivite;
use Application\Entity\Db\SpecificitePoste;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class SpecificiteActiviteService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param SpecificiteActivite $specificiteActivite
     * @return SpecificiteActivite
     */
    public function create(SpecificiteActivite $specificiteActivite) : SpecificiteActivite
    {
        try {
            $this->getEntityManager()->persist($specificiteActivite);
            $this->getEntityManager()->flush($specificiteActivite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $specificiteActivite;
    }

    /**
     * @param SpecificiteActivite $specificiteActivite
     * @return SpecificiteActivite
     */
    public function update(SpecificiteActivite $specificiteActivite) : SpecificiteActivite
    {
        try {
            $this->getEntityManager()->flush($specificiteActivite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $specificiteActivite;
    }

    /**
     * @param SpecificiteActivite $specificiteActivite
     * @return SpecificiteActivite
     */
    public function historise(SpecificiteActivite $specificiteActivite) : SpecificiteActivite
    {
        try {
            $specificiteActivite->historiser();
            $this->getEntityManager()->flush($specificiteActivite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $specificiteActivite;
    }

    /**
     * @param SpecificiteActivite $specificiteActivite
     * @return SpecificiteActivite
     */
    public function restore(SpecificiteActivite $specificiteActivite) : SpecificiteActivite
    {
        try {
            $specificiteActivite->dehistoriser();
            $this->getEntityManager()->flush($specificiteActivite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $specificiteActivite;
    }

    /**
     * @param SpecificiteActivite $specificiteActivite
     * @return SpecificiteActivite
     */
    public function delete(SpecificiteActivite $specificiteActivite) : SpecificiteActivite
    {
        try {
            $this->getEntityManager()->remove($specificiteActivite);
            $this->getEntityManager()->flush($specificiteActivite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $specificiteActivite;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(SpecificiteActivite::class)->createQueryBuilder('sa')
            ->join('sa.specificite', 'specificite')->addSelect('specificite')
            ->join('sa.activite', 'activite')->addSelect('activite')
            ->join('activite.libelles', 'libelle')->addSelect('libelle')
            ->andWhere('libelle.histoDestruction IS NULL')
            ->join('activite.descriptions', 'description')->addSelect('description')
            ->andWhere('description.histoDestruction IS NULL')
        ;
        return $qb;
    }

    /**
     * @param int|null $id
     * @return SpecificiteActivite|null
     */
    public function getSpecificiteActivite(?int $id) : ?SpecificiteActivite
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('sa.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs SpecificiteActivite partagent le même id [".$id."]");
        }
        return $result;
    }
    public function getRequestSpecificiteActivite(AbstractActionController $controller, string $param = "specificite-activite") : ?SpecificiteActivite
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getSpecificiteActivite($id);
        return $result;
    }

    /**
     * @param SpecificitePoste $specificite
     * @return SpecificiteActivite[]
     */
    public function getSpecificitesActivitesBySpecificite(SpecificitePoste $specificite) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('sa.specificite = :specificite')
            ->setParameter('specificite', $specificite)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}