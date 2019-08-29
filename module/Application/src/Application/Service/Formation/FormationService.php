<?php

namespace Application\Service\Formation;

use Application\Entity\Db\Formation;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FormationService {

    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param string $ordre nom de champ présent dans l'entité
     * @return Formation[]
     */
    public function getFormations($ordre = null)
    {
        $qb = $this->getEntityManager()->getRepository(Formation::class)->createQueryBuilder('formation')
            ->andWhere('formation.histoDestruction IS NULL')
        ;
        if ($ordre) $qb = $qb->orderBy('formation.' . $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return Formation
     */
    public function getFormation($id)
    {
        $qb = $this->getEntityManager()->getRepository(Formation::class)->createQueryBuilder('formation')
            ->andWhere('formation.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs formations portent le même identifiant ['.$id.']', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Formation
     */
    public function getRequestedFormation($controller, $paramName = 'formation')
    {
        $id = $controller->params()->fromRoute($paramName);
        $activite = $this->getFormation($id);
        return $activite;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function create($formation)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $formation->setHistoCreation($date);
        $formation->setHistoCreateur($user);
        $formation->setHistoModification($date);
        $formation->setHistoModificateur($user);

        $this->getEntityManager()->persist($formation);
        try {
            $this->getEntityManager()->flush($formation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la création en BD', $e);
        }
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function update($formation)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $formation->setHistoModification($date);
        $formation->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($formation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la mise à jour en BD', $e);
        }
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function historise($formation)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $formation->setHistoDestruction($date);
        $formation->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($formation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la mise à jour en BD', $e);
        }
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function restore($formation)
    {
        $formation->setHistoDestruction(null);
        $formation->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($formation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la mise à jour en BD', $e);
        }
        return $formation;
    }

    /**
     * @param Formation $formation
     */
    public function delete($formation)
    {
        $this->getEntityManager()->remove($formation);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la suppression en BD', $e);
        }
    }

    /**
     * @return array
     */
    public function getFormationsAsOptions() {
        $formations = $this->getFormations('libelle');

        $result = [];
        foreach ($formations as $formation) {
            $result[$formation->getId()] = $formation->getLibelle();
        }
        return $result;
    }
}