<?php

namespace Carriere\Service\Categorie;

use Carriere\Entity\Db\Categorie;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class CategorieService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Categorie $categorie): Categorie
    {
        $this->getObjectManager()->persist($categorie);
        $this->getObjectManager()->flush($categorie);
        return $categorie;
    }

    public function update(Categorie $categorie): Categorie
    {
        $this->getObjectManager()->flush($categorie);
        return $categorie;
    }

    public function historise(Categorie $categorie): Categorie
    {
        $categorie->historiser();
        $this->getObjectManager()->flush($categorie);
        return $categorie;
    }

    public function restore(Categorie $categorie): Categorie
    {
        $categorie->dehistoriser();
        $this->getObjectManager()->flush($categorie);
        return $categorie;
    }

    public function delete(Categorie $categorie): Categorie
    {
        $this->getObjectManager()->remove($categorie);
        $this->getObjectManager()->flush($categorie);
        return $categorie;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuider(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Categorie::class)->createQueryBuilder('categorie');
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

    public function getCategorieByCode(?string $code): ?Categorie
    {
        $qb = $this->createQueryBuider()
            ->andWhere('categorie.code = :code')
            ->setParameter('code', $code);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Categorie::class."] partagent le même code [" . $code . "]", 0, $e);
        }
        return $result;
    }

    public function getCategorieByLibelle(?string $libelle): ?Categorie
    {
        $qb = $this->createQueryBuider()
            ->andWhere('categorie.libelle = :libelle')
            ->setParameter('libelle', $libelle);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Categorie::class."] partagent le même libellé [" . $libelle . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedCategorie(AbstractActionController $controller, string $param = 'categorie'): ?Categorie
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCategorie($id);
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function createWith(string $categorieLibelle, bool $persist = true): ?Categorie
    {
        $categorie = new Categorie();
        $categorie->setCode($categorieLibelle);
        $categorie->setLibelle($categorieLibelle);
        if ($persist) $this->create($categorie);
        return $categorie;
    }

    /** @return Categorie[] */
    public function generateDictionnaire(string $discriminant = 'libelle'): array
    {
        $categories = $this->getCategories();
        $dictionnaire = [];
        foreach ($categories as $categorie) {
            $tabId = match($discriminant) {
                'libelle' => $categorie->getLibelle(),
                'code' => $categorie->getCode(),
                default => $categorie->getId(),
            };
            $dictionnaire[$tabId] = $categorie;
        }
        return $dictionnaire;
    }

}