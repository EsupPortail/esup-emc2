<?php

namespace Application\Service\FichePoste;

use Application\Entity\Db\FichePoste;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FichePosteService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function create($fiche)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $fiche->setHistoCreation($date);
        $fiche->setHistoCreateur($user);
        $fiche->setHistoModification($date);
        $fiche->setHistoModificateur($user);

        $this->getEntityManager()->persist($fiche);
        try {
            $this->getEntityManager()->flush($fiche);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la création en BD', $e);
        }
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function update($fiche)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $fiche->setHistoModification($date);
        $fiche->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($fiche);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la mise à jour en BD', $e);
        }
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function historise($fiche)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $fiche->setHistoDestruction($date);
        $fiche->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($fiche);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de l\'historisation en BD', $e);
        }
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function restore($fiche)
    {
        $fiche->setHistoDestruction(null);
        $fiche->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($fiche);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la restauration en BD', $e);
        }
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function delete($fiche)
    {

        $this->getEntityManager()->remove($fiche);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la restauration en BD', $e);
        }
        return $fiche;
    }

    /**
     * @return FichePoste[]
     */
    public function getFichesPostes()
    {
        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->orderBy('fiche.id', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return FichePoste
     */
    public function getFichePoste($id)
    {
        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->andWhere('fiche.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FichePoste paratagent le même identifiant [".$id."]",$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return FichePoste
     */
    public function getRequestedFichePoste($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $fiche = $this->getFichePoste($id);
        return $fiche;

    }
}