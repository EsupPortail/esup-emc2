<?php

namespace Carriere\Service\Categorie;

use Carriere\Entity\Db\Categorie;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class CategorieService
{
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Categorie $categorie): Categorie
    {
        try {
            $this->getEntityManager()->persist($categorie);
            $this->getEntityManager()->flush($categorie);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", 0, $e);
        }
        return $categorie;
    }

    public function update(Categorie $categorie): Categorie
    {
        try {
            $this->getEntityManager()->flush($categorie);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", 0, $e);
        }
        return $categorie;
    }

    public function historise(Categorie $categorie): Categorie
    {
        try {
            $categorie->historiser();
            $this->getEntityManager()->flush($categorie);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", 0, $e);
        }
        return $categorie;
    }

    public function restore(Categorie $categorie): Categorie
    {
        try {
            $categorie->dehistoriser();
            $this->getEntityManager()->flush($categorie);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", 0, $e);
        }
        return $categorie;
    }

    public function delete(Categorie $categorie): Categorie
    {
        try {
            $this->getEntityManager()->remove($categorie);
            $this->getEntityManager()->flush($categorie);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", 0, $e);
        }
        return $categorie;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuider(): QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(Categorie::class)->createQueryBuilder('categorie');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenulors de la création du QueryBuilder de [" . Categorie::class . "]", 0, $e);
        }
        return $qb;
    }

    /** @return Categorie[] */
    public function getCategories(string $champ = 'libelle', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuider()
            ->orderBy('categorie.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCategorieAsOption(string $champ = 'libelle', string $ordre = 'ASC'): array
    {
        $categories = $this->getCategories($champ, $ordre);
        $array = [];
        foreach ($categories as $categorie) {
            $array[$categorie->getId()] = $categorie->getCode() . " - " . $categorie->getLibelle();
        }
        return $array;
    }

    public function getCategorie(?int $id): ?Categorie
    {
        $qb = $this->createQueryBuider()
            ->andWhere('categorie.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Categorie partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedCategorie(AbstractActionController $controller, string $param = 'categorie'): ?Categorie
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCategorie($id);
        return $result;
    }
}