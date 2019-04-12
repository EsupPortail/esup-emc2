<?php

namespace Application\Service\FicheMetier;

use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheMetierType;
use Application\Entity\Db\FicheTypeExterne;
use Application\Entity\Db\SpecificitePoste;
use Exception;
use Utilisateur\Service\User\UserServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractController;

class FicheMetierService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param string $order an attribute use to sort
     * @return FichePoste[]
     */
    public function getFichesMetiers($order = 'id')
    {
        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('ficheMetier')
            ->orderBy('ficheMetier.', $order)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return FichePoste
     */
    public function getFicheMetier($id)
    {
        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('ficheMetier')
            ->andWhere('ficheMetier.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs fiches métiers portent le même identifiant [".$id."].");
        }
        return $result;
    }


    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function historiser($fiche) {
        //TODO récupérer l'utilisateur connecté
        $utilisateur = null;
        $fiche->historiser($utilisateur);
        try {
            $this->getEntityManager()->flush($fiche);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'historsation de la fiche métier [".$fiche->getId()."].");
        }
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function restaurer($fiche) {
        $fiche->dehistoriser();
        try {
            $this->getEntityManager()->flush($fiche);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la restauration de la fiche métier [".$fiche->getId()."].");
        }
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function creer($fiche)
    {
        $connectedUtilisateur = $this->getUserService()->getConnectedUser();

        $fiche->setHistoCreation(new DateTime());
        $fiche->setHistoCreateur($connectedUtilisateur);
        $fiche->setHistoModification(new DateTime());
        $fiche->setHistoModificateur($connectedUtilisateur);
        $this->getEntityManager()->persist($fiche);
        try {
            $this->getEntityManager()->flush($fiche);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la création de la fiche.");
        }

        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function update($fiche)
    {
        $connectedUtilisateur = $this->getUserService()->getConnectedUser();

        $fiche->setHistoModification(new DateTime());
        $fiche->setHistoModificateur($connectedUtilisateur);
        try {
            $this->getEntityManager()->flush($fiche);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour de la fiche.");
        }

        return $fiche;
    }

    /**
     * @return FicheMetierType[]
     */
    public function getFichesMetiersTypes()
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetierType::class)->createQueryBuilder('fiche')
            ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return FicheMetierType
     */
    public function getFicheMetierType($id)
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetierType::class)->createQueryBuilder('fiche')
            ->andWhere('fiche.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs fiche métier type partagent sur le même identifiant [".$id."].");
        }
        return $result;
    }

    /**
     * @param AbstractController $controller
     * @param string $name
     * @param bool $notNull
     * @return FicheMetierType
     */
    public function getRequestedFicheMetierType($controller, $name, $notNull = false)
    {
        $ficheId = $controller->params()->fromRoute($name);
        $fiche = $this->getFicheMetierType($ficheId);
        if($notNull && !$fiche) throw new RuntimeException("Aucune fiche de trouvée avec l'identifiant [".$ficheId."]");

        return $fiche;
    }

    /**
     * @param FicheMetierType $ficheMetierType
     * @return FicheMetierType
     */
    public function createFicheMetierType($ficheMetierType)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $ficheMetierType->setHistoCreation($date);
        $ficheMetierType->setHistoCreateur($user);
        $ficheMetierType->setHistoModification($date);
        $ficheMetierType->setHistoModificateur($user);

        $this->getEntityManager()->persist($ficheMetierType);
        try {
            $this->getEntityManager()->flush($ficheMetierType);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour de la fiche métier.", $e);
        }
        return $ficheMetierType;

    }

    /**
     * @param FicheMetierType $ficheMetierType
     * @return FicheMetierType
     */
    public function updateFicheMetierType($ficheMetierType)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $ficheMetierType->setHistoModification($date);
        $ficheMetierType->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($ficheMetierType);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour de la fiche métier.", $e);
        }
        return $ficheMetierType;
    }

    /**
     * @param FicheMetierType $ficheMetierType
     * @return FicheMetierType
     */
    public function historiserFicheMetierType($ficheMetierType)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $ficheMetierType->setHistoDestruction($date);
        $ficheMetierType->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($ficheMetierType);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'historisation de la fiche métier.", $e);
        }
        return $ficheMetierType;
    }

    /**
     * @param FicheMetierType $ficheMetierType
     * @return FicheMetierType
     */
    public function restaurationFicheMetierType($ficheMetierType)
    {
        $ficheMetierType->setHistoDestruction(null);
        $ficheMetierType->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($ficheMetierType);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la restauration de la fiche métier.", $e);
        }
        return $ficheMetierType;
    }

    /**
     * @param FicheMetierType $ficheMetierType
     * @return FicheMetierType
     */
    public function deleteFicheMetierType($ficheMetierType)
    {
        $this->getEntityManager()->remove($ficheMetierType);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'effacement de la fiche métier.", $e);
        }
        return $ficheMetierType;
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