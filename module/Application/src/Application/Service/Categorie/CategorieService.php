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
    public function create(Categorie $categorie)
    {
        $this->createFromTrait($categorie);
        return $categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function update(Categorie $categorie)
    {
        $this->updateFromTrait($categorie);
        return $categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function historise(Categorie $categorie)
    {
        $this->historiserFromTrait($categorie);
        return $categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function restore(Categorie $categorie)
    {
        $this->restoreFromTrait($categorie);
        return $categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function delete(Categorie $categorie)
    {
        $this->deleteFromTrait($categorie);
        return $categorie;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuider()
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
    public function getCategories($champ='libelle', $ordre='ASC')
    {
        $qb = $this->createQueryBuider()
            ->orderBy('categorie.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getCategorieAsOption()
    {
        $categories = $this->getCategories();
        $array = [];
        foreach ($categories as $categorie) {
            $array[$categorie->getId()] = $categorie->getCode() ." - ".$categorie->getLibelle();
        }
        return $array;
    }

    /**
     * @param $id
     * @return Categorie
     */
    public function getCategorie($id)
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
     * @return Categorie
     */
    public function getRequestedCategorie(AbstractActionController $controller, $param='categorie')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCategorie($id);
        return $result;
    }

    /**
     * @param string $code
     * @return Categorie
     */
    public function getCategorieByCode(string $code)
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