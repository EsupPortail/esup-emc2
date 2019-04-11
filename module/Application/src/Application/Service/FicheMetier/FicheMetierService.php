<?php

namespace Application\Service\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\FicheMetierType;
use Application\Entity\Db\FicheTypeExterne;
use Application\Entity\Db\SpecificitePoste;
use Application\Service\User\UserServiceAwareTrait;
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
     * @return FicheMetier[]
     */
    public function getFichesMetiers($order = 'id')
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('ficheMetier')
            ->orderBy('ficheMetier.', $order)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return FicheMetier
     */
    public function getFicheMetier($id)
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('ficheMetier')
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
     * @param FicheMetier $fiche
     * @return FicheMetier
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
     * @param FicheMetier $fiche
     * @return FicheMetier
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
     * @param FicheMetier $fiche
     * @return FicheMetier
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
     * @param FicheMetier $fiche
     * @return FicheMetier
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
    public function createFicheMetierType( $ficheMetierType)
    {
        $this->getEntityManager()->persist($ficheMetierType);
        try {
            $this->getEntityManager()->flush($ficheMetierType);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour de la fiche métier.", $e);
        }
        return $ficheMetierType;

    }

    public function updateFicheMetierType($ficheMetierType)
    {
        try {
            $this->getEntityManager()->flush($ficheMetierType);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour de la fiche métier.", $e);
        }
        return $ficheMetierType;
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