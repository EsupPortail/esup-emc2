<?php

namespace Application\Service\Metier;

use Application\Entity\Db\Metier;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class MetierService {
    use EntityManagerAwareTrait;

    /**
     * @return Metier[]
     */
    public function getMetiers()
    {
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
            ->addSelect('fonction')->leftJoin('metier.fonction','fonction')
            ->addSelect('domaine')->leftJoin('fonction.domaine','domaine')
            ->addSelect('famille')->leftJoin('domaine.famille','famille')
        ;
        $qb = $qb->addOrderBy('metier.libelle');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getMetiersAsOptions()
    {
        $metiers = $this->getMetiers();

        $array = [];
        foreach ($metiers as $metier) {
            $array[$metier->getId()] = $metier->getLibelle();
        }
        return $array;
    }

    /**
     * @param integer $id
     * @return Metier
     */
    public function getMetier($id)
    {
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
            ->addSelect('fonction')->leftJoin('metier.fonction','fonction')
            ->addSelect('domaine')->leftJoin('fonction.domaine','domaine')
            ->addSelect('famille')->leftJoin('domaine.famille','famille')
            ->andWhere('metier.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Metier partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Metier
     */
    public function getRequestedMetier($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $metier = $this->getMetier($id);

        return $metier;
    }

        /**
     * @param Metier $metier
     * @return Metier
     */
    public function create($metier)
    {
        $this->getEntityManager()->persist($metier);
        try {
            $this->getEntityManager()->flush($metier);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la création d'un Metier", $e);
        }
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function update($metier)
    {
        try {
            $this->getEntityManager()->flush($metier);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Metier.", $e);
        }
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function delete($metier)
    {
        $this->getEntityManager()->remove($metier);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la suppression d'un Metier", $e);
        }
        return $metier;
    }
}