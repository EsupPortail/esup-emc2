<?php

namespace Application\Service\Domaine;

use Application\Entity\Db\Domaine;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class DomaineService {
    use EntityManagerAwareTrait;

    /**
     * @return Domaine[]
     */
    public function getDomaines()
    {
        $qb = $this->getEntityManager()->getRepository(Domaine::class)->createQueryBuilder('domaine')
            ->addSelect('famille')->leftJoin('domaine.famille', 'famille')
            ->addSelect('fonction')->leftJoin('domaine.fonctions', 'fonction')
            ->addSelect('metier')->leftJoin('fonction.metiers', 'metier')
        ;
        $qb = $qb->addOrderBy('domaine.libelle', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getDomainesAsOptions()
    {
        $domaines = $this->getDomaines();

        $options = [];
        foreach ($domaines as $domaine) {
            $options[$domaine->getId()] = $domaine->getLibelle();
        }

        return $options;
    }

    /**
     * @param integer $id
     * @return Domaine
     */
    public function getDomaine($id)
    {
        $qb = $this->getEntityManager()->getRepository(Domaine::class)->createQueryBuilder('domaine')
            ->addSelect('famille')->leftJoin('domaine.famille', 'famille')
            ->addSelect('fonction')->leftJoin('domaine.fonctions', 'fonction')
            ->addSelect('metier')->leftJoin('fonction.metiers', 'metier')
            ->andWhere('domaine.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Domaine partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Domaine
     */
    public function getRequestedDomaine($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $domaine = $this->getDomaine($id);

        return $domaine;
    }

    /**
     * @param Domaine $domaine
     * @return Domaine
     */
    public function create($domaine)
    {
        $this->getEntityManager()->persist($domaine);
        try {
            $this->getEntityManager()->flush($domaine);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la création d'un Domaine", $e);
        }
        return $domaine;
    }

    /**
     * @param Domaine $domaine
     * @return Domaine
     */
    public function update($domaine)
    {
        try {
            $this->getEntityManager()->flush($domaine);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Domaine.", $e);
        }
        return $domaine;
    }

    /**
     * @param Domaine $domaine
     */
    public function delete($domaine)
    {
        $this->getEntityManager()->remove($domaine);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la suppression d'un Domaine", $e);
        }
    }

}