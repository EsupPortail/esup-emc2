<?php

namespace UnicaenParametre\Service\Categorie;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenParametre\Entity\Db\Categorie;
use Zend\Mvc\Controller\AbstractActionController;

class CategorieService {
    use EntityManagerAwareTrait;

    /** GESTION ENTITY ************************************************************************************************/

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function create(Categorie $categorie) : Categorie
    {
        try {
            $this->getEntityManager()->persist($categorie);
            $this->getEntityManager()->flush($categorie);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'enregistrement en base.",0,$e);
        }
        return $categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function update(Categorie $categorie) : Categorie
    {
        try {
            $this->getEntityManager()->flush($categorie);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour en base.",0,$e);
        }
        return $categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function delete(Categorie $categorie) : Categorie
    {
        try {
            $this->getEntityManager()->remove($categorie);
            $this->getEntityManager()->flush($categorie);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la suppression en base.",0,$e);
        }
        return $categorie;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Categorie::class)->createQueryBuilder('categorie');
        return $qb;
    }
    /**
     * @param string $champ
     * @param string $ordre
     * @return Categorie[]
     */
    public function getCategories(string $champ="ordre", string $ordre="ASC") : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('categorie.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getCategoriesAsOptions(string $champ="ordre", string $ordre="ASC") : array
    {
        $categories = $this->getCategories($champ,$ordre);
        $array = [];
        foreach ($categories as $categorie) {
            $array[$categorie->getId()] = $categorie->getLibelle();
        }
        return $array;
    }

    /**
     * @param int|null $id
     * @return Categorie|null
     */
    public function getCategorie(?int $id) : ?Categorie
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('categorie.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ParamatreCategorie partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    /**
     * @param string $code
     * @return Categorie|null
     */
    public function getCategoriebyCode(string $code) : ?Categorie
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('categorie.code = :code')
            ->setParameter('code', $code)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ParamatreCategorie partagent le même code [".$code."]",0,$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Categorie|null
     */
    public function getRequestedCategorie(AbstractActionController $controller, string $param='categorie') : ?Categorie
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCategorie($id);
        return $result;
    }
}