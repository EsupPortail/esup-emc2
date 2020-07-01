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
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FormulaireService {
    use EntityManagerAwareTrait;
    use CategorieServiceAwareTrait;
    use UserServiceAwareTrait;
    use DateTimeAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Formulaire $formulaire
     * @return Formulaire
     */
    public function create($formulaire)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $formulaire->setHistoCreateur($user);
        $formulaire->setHistoCreation($date);
        $formulaire->setHistoModificateur($user);
        $formulaire->setHistoModification($date);

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
    public function update($formulaire)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $formulaire->setHistoModificateur($user);
        $formulaire->setHistoModification($date);

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
    public function historise($formulaire)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $formulaire->setHistoDestructeur($user);
        $formulaire->setHistoDestruction($date);

        try {
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
    public function restaure($formulaire)
    {
        $formulaire->setHistoDestructeur(null);
        $formulaire->setHistoDestruction(null);

        try {
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
    public function delete($formulaire)
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
    public function createQueryBuilder()
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
    public function getFormulaires()
    {
        $qb = $this->createQueryBuilder();

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Formulaire
     */
    public function getFormulaire($id)
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
     * @param string $code
     * @return Formulaire
     */
    public function getFormulaireByCode($code)
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
    public function getRequestedFormulaire($controller, $label)
    {
        $id = $controller->params()->fromRoute($label);
        $formulaire = $this->getFormulaire($id);
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

    /**
     * @param Formulaire $formulaire
     * @return array
     */
    public function getChampsAsOptions(Formulaire $formulaire)
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
