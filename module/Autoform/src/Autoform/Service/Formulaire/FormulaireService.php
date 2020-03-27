<?php

namespace Autoform\Service\Formulaire;

use Autoform\Entity\Db\Formulaire;
use Autoform\Service\Categorie\CategorieServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FormulaireService {
    use EntityManagerAwareTrait;
    use CategorieServiceAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param AbstractActionController $controller
     * @param string $label
     * @return Formulaire
     */
    public function getRequestedFormulaire($controller, $label)
    {
        $id = $controller->params()->fromRoute($label);
        $formulaire = $this->getFormulaire($id);
        return $formulaire;
    }
    /**
     * @return Formulaire[]
     */
    public function getFormulaires()
    {
        $qb = $this->getEntityManager()->getRepository(Formulaire::class)->createQueryBuilder('formulaire');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Formulaire
     */
    public function getFormulaire($id)
    {
        $qb = $this->getEntityManager()->getRepository(Formulaire::class)->createQueryBuilder('formulaire')
            ->addSelect('categorie')->leftJoin('formulaire.categories', 'categorie')
            ->addSelect('champ')->leftJoin('categorie.champs', 'champ')
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
     * @param Formulaire $formulaire
     * @return Formulaire
     */
    public function create($formulaire)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (\Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $formulaire->setHistoCreateur($user);
        $formulaire->setHistoCreation($date);
        $formulaire->setHistoModificateur($user);
        $formulaire->setHistoModification($date);

        $this->getEntityManager()->persist($formulaire);
        try {
            $this->getEntityManager()->flush($formulaire);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la création d'un Formulaire.", $e);
        }
        return $formulaire;
    }

    /**
     * @param Formulaire $formulaire
     * @return Formulaire
     */
    public function update($formulaire)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (\Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $formulaire->setHistoModificateur($user);
        $formulaire->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($formulaire);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Formulaire.", $e);
        }
        return $formulaire;
    }

    /**
     * @param Formulaire $formulaire
     * @return Formulaire
     */
    public function historise($formulaire)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (\Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $formulaire->setHistoDestructeur($user);
        $formulaire->setHistoDestruction($date);

        try {
            $this->getEntityManager()->flush($formulaire);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de l'historisation d'un Formulaire.", $e);
        }
        return $formulaire;
    }

    /**
     * @param Formulaire $formulaire
     * @return Formulaire
     */
    public function restaure($formulaire)
    {
        $formulaire->setHistoDestructeur(null);
        $formulaire->setHistoDestruction(null);

        try {
            $this->getEntityManager()->flush($formulaire);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la restauration d'un Formulaire.", $e);
        }
        return $formulaire;
    }

    /**
     * @param Formulaire $formulaire
     * @return Formulaire
     */
    public function delete($formulaire)
    {
        $this->getEntityManager()->remove($formulaire);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la suppression d'un Formulaire.", $e);
        }
        return $formulaire;
    }


    public function compacter($formulaire) {
        $categories = $this->getCategorieService()->getCategoriesByFormulaire($formulaire, 'ordre');

        $position = 1;
        foreach ($categories as $categorie) {
            $categorie->setOrdre($position);
            $this->getCategorieService()->update($categorie);
            $position++;
        }
    }
}