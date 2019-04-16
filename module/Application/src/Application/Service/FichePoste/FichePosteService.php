<?php

namespace Application\Service\FichePoste;

use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheTypeExterne;
use Application\Entity\Db\SpecificitePoste;
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

    /** SPECIFICITE POSTE  ********************************************************************************************/

    /**
     * @return SpecificitePoste[]
     */
    public function getSpecificitesPostes() {
        $qb = $this->getEntityManager()->getRepository(SpecificitePoste::class)->createQueryBuilder('specificite')
            ->orderBy('specificite.id', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return SpecificitePoste
     */
    public function getSpecificitePoste($id)
    {
        $qb = $this->getEntityManager()->getRepository(SpecificitePoste::class)->createQueryBuilder('specificite')
            ->andWhere('specificite.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs spécificités partagent sur le même identifiant [".$id."].");
        }
        return $result;
    }

    /**
     * @param SpecificitePoste $specificite
     * @return SpecificitePoste
     */
    public function createSpecificitePoste($specificite)
    {
        $this->getEntityManager()->persist($specificite);
        try {
            $this->getEntityManager()->flush($specificite);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la création de la spécificité du poste.", $e);
        }
        return $specificite;
    }

    /**
     * @param SpecificitePoste $specificite
     * @return SpecificitePoste
     */
    public function updateSpecificitePoste($specificite)
    {
        try {
            $this->getEntityManager()->flush($specificite);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour de la spécificité du poste.", $e);
        }
        return $specificite;
    }

    /**
     * @param SpecificitePoste $specificite
     */
    public function deleteSpecificitePoste($specificite)
    {
        $this->getEntityManager()->remove($specificite);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'effacement de la spécificité du poste.", $e);
        }
    }

    /** FICHE TYPE EXTERNE ********************************************************************************************/

    /**
     * @param FicheTypeExterne $ficheTypeExterne
     * @return FicheTypeExterne
     */
    public function createFicheTypeExterne($ficheTypeExterne)
    {
        $this->getEntityManager()->persist($ficheTypeExterne);
        try {
            $this->getEntityManager()->flush($ficheTypeExterne);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'ajout d'une fiche metier externe.", $e);
        }
        return $ficheTypeExterne;
    }

    /**
     * @param FicheTypeExterne $ficheTypeExterne
     * @return FicheTypeExterne
     */
    public function updateFicheTypeExterne($ficheTypeExterne)
    {
        try {
            $this->getEntityManager()->flush($ficheTypeExterne);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour d'une fiche metier externe.", $e);
        }
        return $ficheTypeExterne;
    }

    /**
     * @param FicheTypeExterne $ficheTypeExterne
     * @return FicheTypeExterne
     */
    public function deleteFicheTypeExterne($ficheTypeExterne)
    {
        $this->getEntityManager()->remove($ficheTypeExterne);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors du retrait d'une fiche metier externe.", $e);
        }
        return $ficheTypeExterne;
    }


    /**
     * @param integer $id
     * @return FicheTypeExterne
     */
    public function getFicheTypeExterne($id)
    {
        $qb = $this->getEntityManager()->getRepository(FicheTypeExterne::class)->createQueryBuilder('externe')
            ->andWhere('externe.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieus FicheTypeExterne partagent le même identifiant [".$id."]",$e);
        }
        return $result;
    }
}