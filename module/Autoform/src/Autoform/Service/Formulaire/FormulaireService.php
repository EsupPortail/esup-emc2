<?php

namespace Autoform\Service\Formulaire;

use Autoform\Entity\Db\Champ;
use Autoform\Entity\Db\Formulaire;
use Autoform\Service\Categorie\CategorieServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FormulaireService {
    use EntityManagerAwareTrait;
    use CategorieServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Formulaire $formulaire
     * @return Formulaire
     */
    public function create(Formulaire $formulaire) : Formulaire
    {
        try {
            $this->getEntityManager()->persist($formulaire);
            $this->getEntityManager()->flush($formulaire);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la création d'un Formulaire.", $e);
        }
        return $formulaire;
    }

    /**
     * @param Formulaire $formulaire
     * @return Formulaire
     */
    public function update(Formulaire $formulaire) : Formulaire
    {
        try {
            $this->getEntityManager()->flush($formulaire);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Formulaire.", $e);
        }
        return $formulaire;
    }

    /**
     * @param Formulaire $formulaire
     * @return Formulaire
     */
    public function historise(Formulaire $formulaire) : Formulaire
    {
        try {
            $formulaire->historiser();
            $this->getEntityManager()->flush($formulaire);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de l'historisation d'un Formulaire.", $e);
        }
        return $formulaire;
    }

    /**
     * @param Formulaire $formulaire
     * @return Formulaire
     */
    public function restaure(Formulaire $formulaire) : Formulaire
    {
        try {
            $formulaire->dehistoriser();
            $this->getEntityManager()->flush($formulaire);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la restauration d'un Formulaire.", $e);
        }
        return $formulaire;
    }

    /**
     * @param Formulaire $formulaire
     * @return Formulaire
     */
    public function delete(Formulaire $formulaire) : Formulaire
    {
        try {
            $this->getEntityManager()->remove($formulaire);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la suppression d'un Formulaire.", $e);
        }
        return $formulaire;
    }

    /** REQUETAGES ****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Formulaire::class)->createQueryBuilder('formulaire')
            ->addSelect('categorie')->leftJoin('formulaire.categories', 'categorie')
            ->addSelect('champ')->leftJoin('categorie.champs', 'champ')
        ;
        return $qb;
    }

    /**
     * @return Formulaire[]
     */
    public function getFormulaires() : array
    {
        $qb = $this->createQueryBuilder();

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int|null $id
     * @return Formulaire|null
     */
    public function getFormulaire(?int $id) : ?Formulaire
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formulaire.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Formulaire partagent le même identifiant [".$id."].", $e);
        }
        return $result;
    }

    /**
     * @param string|null $code
     * @return Formulaire
     */
    public function getFormulaireByCode(?string $code) : ?Formulaire
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formulaire.code = :code')
            ->setParameter('code', $code)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Formulaire partagent le même code [".$code."].", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $label
     * @return Formulaire
     */
    public function getRequestedFormulaire(AbstractActionController $controller, string $label = 'formaulaire') : ?Formulaire
    {
        $id = $controller->params()->fromRoute($label);
        $formulaire = $this->getFormulaire($id);
        return $formulaire;
    }

    /**
     * @param Formulaire $formulaire
     */
    public function compacter(Formulaire $formulaire)
    {
        $categories = $this->getCategorieService()->getCategoriesByFormulaire($formulaire, 'ordre');

        $position = 1;
        foreach ($categories as $categorie) {
            $categorie->setOrdre($position);
            $this->getCategorieService()->update($categorie);
            $position++;
        }
    }

    /**
     * @param Formulaire $formulaire
     * @return array
     */
    public function getChampsAsOptions(Formulaire $formulaire) : array
    {
        $champs = [];
        foreach ($formulaire->getCategories() as  $categorie) {
            foreach ($categorie->getChamps() as $champ) {
                if ($champ->getElement() !== 'Spacer' AND $champ->getElement() !== 'Label')
                    $champs[] = $champ;
            }
        }

        usort($champs, function(Champ $a, Champ $b) { return $a->getLibelle() > $b->getLibelle();});

        $array = [];
        foreach ($champs as $champ) {
            $array[$champ->getId()] = $champ->getLibelle();
        }

        return $array;
    }
}
