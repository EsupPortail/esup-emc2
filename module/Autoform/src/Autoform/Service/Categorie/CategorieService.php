<?php

namespace Autoform\Service\Categorie;

use Autoform\Entity\Db\Categorie;
use Autoform\Entity\Db\Formulaire;
use Autoform\Service\Champ\ChampServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class CategorieService {
    use ChampServiceAwareTrait;
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;
    use DateTimeAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function create($categorie)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $categorie->setHistoCreateur($user);
        $categorie->setHistoCreation($date);
        $categorie->setHistoModificateur($user);
        $categorie->setHistoModification($date);

        try {
            $this->getEntityManager()->persist($categorie);
            $this->getEntityManager()->flush($categorie);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la création d'un Categorie.", $e);
        }
        return $categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function update($categorie)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $categorie->setHistoModificateur($user);
        $categorie->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($categorie);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Categorie.", $e);
        }
        return $categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function historise($categorie)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $categorie->setHistoDestructeur($user);
        $categorie->setHistoDestruction($date);

        try {
            $this->getEntityManager()->flush($categorie);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de l'historisation d'un Categorie.", $e);
        }
        return $categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function restaure($categorie)
    {
        $categorie->setHistoDestructeur(null);
        $categorie->setHistoDestruction(null);

        try {
            $this->getEntityManager()->flush($categorie);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la restauration d'un Categorie.", $e);
        }
        return $categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Categorie
     */
    public function delete($categorie)
    {
        try {
            $this->getEntityManager()->remove($categorie);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la suppression d'un Categorie.", $e);
        }
        return $categorie;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @param AbstractActionController $controller
     * @param string $label
     * @return Categorie
     */
    public function getRequestedCategorie($controller, $label)
    {
        $id = $controller->params()->fromRoute($label);
        $categorie = $this->getCategorie($id);
        return $categorie;
    }

    /**
     * @return Categorie[]
     */
    public function getCategories()
    {
        $qb = $this->getEntityManager()->getRepository(Categorie::class)->createQueryBuilder('categorie');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Formulaire $formulaire
     * @param string $ordre
     * @return Categorie[]
     */
    public function getCategoriesByFormulaire($formulaire, $ordre = null)
    {
        $qb = $this->getEntityManager()->getRepository(Categorie::class)->createQueryBuilder('categorie')
            ->andWhere('categorie.formulaire = :formulaire')
            ->setParameter('formulaire', $formulaire)
        ;

        if ($ordre) $qb = $qb->orderBy('categorie.ordre', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Categorie
     */
    public function getCategorie($id)
    {
        $qb = $this->getEntityManager()->getRepository(Categorie::class)->createQueryBuilder('categorie')
            ->andWhere('categorie.id = :id')
            ->setParameter('id', $id)
            ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Categorie partagent le même identifiant [".$id."].", $e);
        }
        return $result;
    }

    /**
     * @param integer $ordre
     * @return Categorie
     */
    public function getCategorieByOrdre($ordre)
    {
        $qb = $this->getEntityManager()->getRepository(Categorie::class)->createQueryBuilder('categorie')
            ->andWhere('categorie.ordre = :ordre')
            ->setParameter('ordre', $ordre)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Categorie partagent le même ordre [".$ordre."].", $e);
        }
        return $result;
    }

    /**
     * @param Categorie $categorie1
     * @param Categorie $categorie2
     */
    public function swapCategories($categorie1, $categorie2)
    {
        $buffer = $categorie1->getOrdre();
        $categorie1->setOrdre($categorie2->getOrdre());
        $categorie2->setOrdre($buffer);
        $this->update($categorie1);
        $this->update($categorie2);
    }

    /**
     * @param Categorie $categorie
     * @param string $direction
     * @return Categorie[]
     */
    public function getCategoriesAvecSens($categorie, $direction)
    {
        $qb = $this->getEntityManager()->getRepository(Categorie::class)->createQueryBuilder('categorie')
            ->andWhere('categorie.formulaire = :formulaire')
            ->setParameter('formulaire', $categorie->getFormulaire())
        ;

        switch($direction) {
            case 'haut' :
                $qb = $qb->andWhere('categorie.ordre < :position')
                    ->setParameter('position', $categorie->getOrdre())
                    ->orderBy('categorie.ordre', 'DESC')
                ;
                break;
            case 'bas' :
                $qb = $qb->andWhere('categorie.ordre > :position')
                    ->setParameter('position', $categorie->getOrdre())
                    ->orderBy('categorie.ordre', 'ASC')
                ;
                break;
            default:
                throw new RuntimeException("Direction non reconnue");
                break;
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function compacter($categorie) {
        $champs = $this->getChampService()->getChampsByCategorie($categorie, 'ordre');

        $position = 1;
        foreach ($champs as $champ) {
            $champ->setOrdre($position);
            $this->getChampService()->update($champ);
            $position++;
        }
    }

}