<?php

namespace Autoform\Service\Champ;

use Autoform\Entity\Db\Categorie;
use Autoform\Entity\Db\Champ;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ChampService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param AbstractActionController $controller
     * @param string $label
     * @return Champ
     */
    public function getRequestedChamp($controller, $label)
    {
        $id = $controller->params()->fromRoute($label);
        $champ = $this->getChamp($id);
        return $champ;
    }

    /**
     * @return Champ[]
     */
    public function getChamps()
    {
        $qb = $this->getEntityManager()->getRepository(Champ::class)->createQueryBuilder('champ');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Categorie $categorie
     * @param string $ordre
     * @return Champ[]
     */
    public function getChampsByCategorie($categorie, $ordre = null)
    {
        $qb = $this->getEntityManager()->getRepository(Champ::class)->createQueryBuilder('champ')
            ->andWhere('champ.categorie = :categorie')
            ->setParameter('categorie', $categorie)
        ;

        if ($ordre) $qb = $qb->orderBy('champ.ordre', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Champ
     */
    public function getChamp($id)
    {
        $qb = $this->getEntityManager()->getRepository(Champ::class)->createQueryBuilder('champ')
            ->andWhere('champ.id = :id')
            ->setParameter('id', $id)
            ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Champ partagent le même identifiant [".$id."].", $e);
        }
        return $result;
    }

    /**
     * @param Champ $champ
     * @return Champ
     */
    public function create($champ)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (\Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $champ->setHistoCreateur($user);
        $champ->setHistoCreation($date);
        $champ->setHistoModificateur($user);
        $champ->setHistoModification($date);

        $this->getEntityManager()->persist($champ);
        try {
            $this->getEntityManager()->flush($champ);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la création d'un Champ.", $e);
        }
        return $champ;
    }

    /**
     * @param Champ $champ
     * @return Champ
     */
    public function update($champ)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (\Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $champ->setHistoModificateur($user);
        $champ->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($champ);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Champ.", $e);
        }
        return $champ;
    }

    /**
     * @param Champ $champ
     * @return Champ
     */
    public function historise($champ)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (\Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $champ->setHistoDestructeur($user);
        $champ->setHistoDestruction($date);

        try {
            $this->getEntityManager()->flush($champ);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de l'historisation d'un Champ.", $e);
        }
        return $champ;
    }

    /**
     * @param Champ $champ
     * @return Champ
     */
    public function restaure($champ)
    {
        $champ->setHistoDestructeur(null);
        $champ->setHistoDestruction(null);

        try {
            $this->getEntityManager()->flush($champ);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la restauration d'un Champ.", $e);
        }
        return $champ;
    }

    /**
     * @param Champ $champ
     * @return Champ
     */
    public function delete($champ)
    {
        $this->getEntityManager()->remove($champ);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la suppression d'un Champ.", $e);
        }
        return $champ;
    }



    /**
     * @param integer $ordre
     * @return Champ
     */
    public function getChampByOrdre($ordre)
    {
        $qb = $this->getEntityManager()->getRepository(Champ::class)->createQueryBuilder('champ')
            ->andWhere('champ.ordre = :ordre')
            ->setParameter('ordre', $ordre)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Champ partagent le même ordre [".$ordre."].", $e);
        }
        return $result;
    }

    /**
     * @param Champ $champ1
     * @param Champ $champ2
     */
    public function swapChamps($champ1, $champ2)
    {
        $buffer = $champ1->getOrdre();
        $champ1->setOrdre($champ2->getOrdre());
        $champ2->setOrdre($buffer);
        $this->update($champ1);
        $this->update($champ2);
    }

    /**
     * @param Champ $champ
     * @param string $direction
     * @return Champ[]
     */
    public function getChampsAvecSens($champ, $direction)
    {
        $qb = $this->getEntityManager()->getRepository(Champ::class)->createQueryBuilder('champ')
            ->andWhere('champ.categorie = :categorie')
            ->setParameter('categorie', $champ->getCategorie())
        ;

        switch($direction) {
            case 'haut' :
                $qb = $qb->andWhere('champ.ordre < :position')
                    ->setParameter('position', $champ->getOrdre())
                    ->orderBy('champ.ordre', 'DESC')
                ;
                break;
            case 'bas' :
                $qb = $qb->andWhere('champ.ordre > :position')
                    ->setParameter('position', $champ->getOrdre())
                    ->orderBy('champ.ordre', 'ASC')
                ;
                break;
            default:
                throw new RuntimeException("Direction non reconnue");
                break;
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }


    public function getAllInstance($entity)
    {
        $instances = $this->getEntityManager()->getRepository($entity)->findAll();
        usort($instances, function($a, $b) {return $a->getLibelle() > $b->getLibelle();});
        $array = [];
        foreach ($instances as $instance) {
            $array[$instance->getId()] = $instance->getLibelle();
        }
        return $array;
    }




}