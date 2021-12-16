<?php

namespace Autoform\Service\Champ;

use Autoform\Entity\Db\Categorie;
use Autoform\Entity\Db\Champ;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ChampService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Champ $champ
     * @return Champ
     */
    public function create(Champ $champ) : Champ
    {
        try {
            $this->getEntityManager()->persist($champ);
            $this->getEntityManager()->flush($champ);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la création d'un Champ.", $e);
        }
        return $champ;
    }

    /**
     * @param Champ $champ
     * @return Champ
     */
    public function update(Champ $champ) : Champ
    {
        try {
            $this->getEntityManager()->flush($champ);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Champ.", $e);
        }
        return $champ;
    }

    /**
     * @param Champ $champ
     * @return Champ
     */
    public function historise(Champ $champ) : Champ
    {
        try {
            $champ->historiser();
            $this->getEntityManager()->flush($champ);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Champ.", $e);
        }
        return $champ;
    }

    /**
     * @param Champ $champ
     * @return Champ
     */
    public function restaure(Champ $champ) : Champ
    {
        try {
            $champ->historiser();
            $this->getEntityManager()->flush($champ);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Champ.", $e);
        }
        return $champ;
    }

    /**
     * @param Champ $champ
     * @return Champ
     */
    public function delete(Champ $champ) : Champ
    {
        try {
            $this->getEntityManager()->remove($champ);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la suppression d'un Champ.", $e);
        }
        return $champ;
    }

    /** REQUETAGE *****************************************************************************************************/

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
     * @param string $champ
     * @param string $order
     * @return Champ[]
     */
    public function getChamps($champ = 'id', $order = 'ASC')
    {
        $qb = $this->getEntityManager()->getRepository(Champ::class)->createQueryBuilder('champ')
            ->orderBy('champ.' . $champ, $order)
        ;

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