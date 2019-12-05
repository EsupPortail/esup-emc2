<?php

namespace Application\Service\ApplicationsConservees;

use Application\Entity\Db\FicheposteApplicationConservee;
use DateTime;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;

class ApplicationsConserveesService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheposteApplicationConservee $applicationConservee
     * @return FicheposteApplicationConservee
     */
    public function create(FicheposteApplicationConservee $applicationConservee) {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération des informations d'historisation.", 0, $e);
        }
        $applicationConservee->setHistoCreation($date);
        $applicationConservee->setHistoModification($date);
        $applicationConservee->setHistoCreateur($user);
        $applicationConservee->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($applicationConservee);
            $this->getEntityManager()->flush($applicationConservee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0 , $e);
        }

        return $applicationConservee;
    }

    /**
     * @param FicheposteApplicationConservee $applicationConservee
     * @return FicheposteApplicationConservee
     */
    public function update(FicheposteApplicationConservee $applicationConservee) {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération des informations d'historisation.", 0, $e);
        }
        $applicationConservee->setHistoModification($date);
        $applicationConservee->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($applicationConservee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0 , $e);
        }

        return $applicationConservee;
    }

    /**
     * @param FicheposteApplicationConservee $applicationConservee
     * @return FicheposteApplicationConservee
     */
    public function delete(FicheposteApplicationConservee $applicationConservee) {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération des informations d'historisation.", 0, $e);
        }
        $applicationConservee->setHistoDestruction($date);
        $applicationConservee->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($applicationConservee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0 , $e);
        }

        return $applicationConservee;
    }

    /** ACCESSEUR *****************************************************************************************************/


}