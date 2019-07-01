<?php

namespace Application\Service\FamilleProfessionnelle;

use Application\Entity\Db\FamilleProfessionnelle;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FamilleProfessionnelleService {
    use EntityManagerAwareTrait;

    /**
     * @param string $order
     * @return FamilleProfessionnelle[]
     */
    public function getFamillesProfessionnelles()
    {
        $qb = $this->getEntityManager()->getRepository(FamilleProfessionnelle::class)->createQueryBuilder('famille')
            ->addSelect('domaine')->leftJoin('famille.domaines', 'domaine')
            ->addSelect('metier')->leftJoin('domaine.metiers', 'metier')
        ;
        $qb = $qb->addOrderBy('famille.libelle');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFamillesProfessionnellesAsOptions()
    {
        $familles = $this->getFamillesProfessionnelles();
        $options = [];
        foreach ($familles as $famille) {
            $options[$famille->getId()] = $famille->getLibelle();
        }
        return $options;
    }

    /**
     * @param integer $id
     * @return FamilleProfessionnelle
     */
    public function getFamilleProfessionnelle($id)
    {
        $qb = $this->getEntityManager()->getRepository(FamilleProfessionnelle::class)->createQueryBuilder('famille')
            ->addSelect('domaine')->leftJoin('famille.domaines', 'domaine')
            ->addSelect('metier')->leftJoin('domaine.metiers', 'metier')
            ->andWhere('famille.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FamilleProfessionnelle partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return FamilleProfessionnelle
     */
    public function getRequestedFamilleProfessionnelle($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $famille = $this->getFamilleProfessionnelle($id);

        return $famille;
    }

    /**
     * @param FamilleProfessionnelle $famille
     * @return FamilleProfessionnelle
     */
    public function create($famille)
    {
        $this->getEntityManager()->persist($famille);
        try {
            $this->getEntityManager()->flush($famille);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la création d'une FamilleProfessionnelle", $e);
        }
        return $famille;
    }

    /**
     * @param FamilleProfessionnelle $famille
     * @return FamilleProfessionnelle
     */
    public function update($famille)
    {
        try {
            $this->getEntityManager()->flush($famille);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la mise à jour d'une FamilleProfessionnelle.", $e);
        }
        return $famille;
    }

    /**
     * @param FamilleProfessionnelle $famille
     */
    public function delete($famille)
    {
        $this->getEntityManager()->remove($famille);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la suppression d'une FamilleProfessionnelle", $e);
        }
    }
}