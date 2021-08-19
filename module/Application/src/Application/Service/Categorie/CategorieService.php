<?php

namespace Application\Service\Categorie;

use Application\Entity\Db\Categorie;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class CategorieService
{
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function create(Categorie $categorie) : Categorie
    {
        $this->createFromTrait($categorie);
        return $categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function update(Categorie $categorie) : Categorie

    {
        $this->updateFromTrait($categorie);
        return $categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function historise(Categorie $categorie) : Categorie
    {
        $this->historiserFromTrait($categorie);
        return $categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function restore(Categorie $categorie) : Categorie
    {
        $this->restoreFromTrait($categorie);
        return $categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function delete(Categorie $categorie) : Categorie
    {
        $this->deleteFromTrait($categorie);
        return $categorie;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuider() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Categorie::class)->createQueryBuilder('categorie')
            ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Categorie[]
     */
    public function getCategories(string $champ='libelle', string $ordre='ASC') : array
    {
        $qb = $this->createQueryBuider()
            ->orderBy('categorie.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getCategorieAsOption(string $champ='libelle', string $ordre='ASC') : array
    {
        $categories = $this->getCategories($champ,$ordre);
        $array = [];
        foreach ($categories as $categorie) {
            $array[$categorie->getId()] = $categorie->getCode() ." - ".$categorie->getLibelle();
        }
        return $array;
    }

    /**
     * @param int|null $id
     * @return Categorie|null
     */
    public function getCategorie(?int $id) : ?Categorie
    {
        $qb = $this->createQueryBuider()
            ->andWhere('categorie.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Categorie partagent le même id [".$id."]",0,$e);
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

    /**
     * @param string $code
     * @return Categorie|null
     */
    public function getCategorieByCode(string $code) : ?Categorie
    {
        $qb = $this->createQueryBuider()
            ->andWhere('categorie.code = :code')
            ->setParameter('code', $code)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Categorie partagent le même code [".$code."]",0,$e);
        }
        return $result;
    }
}