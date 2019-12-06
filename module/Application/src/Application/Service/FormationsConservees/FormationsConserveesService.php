<?php

namespace Application\Service\FormationsConservees;

use Application\Entity\Db\FicheposteFormationConservee;
use DateTime;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;

class FormationsConserveesService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheposteFormationConservee $formationConservee
     * @return FicheposteFormationConservee
     */
    public function create(FicheposteFormationConservee $formationConservee) {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération des informations d'historisation.", 0, $e);
        }
        $formationConservee->setHistoCreation($date);
        $formationConservee->setHistoModification($date);
        $formationConservee->setHistoCreateur($user);
        $formationConservee->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($formationConservee);
            $this->getEntityManager()->flush($formationConservee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0 , $e);
        }

        return $formationConservee;
    }

    /**
     * @param FicheposteFormationConservee $formationConservee
     * @return FicheposteFormationConservee
     */
    public function update(FicheposteFormationConservee $formationConservee) {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération des informations d'historisation.", 0, $e);
        }
        $formationConservee->setHistoModification($date);
        $formationConservee->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($formationConservee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0 , $e);
        }

        return $formationConservee;
    }

    /**
     * @param FicheposteFormationConservee $formationConservee
     * @return FicheposteFormationConservee
     */
    public function delete(FicheposteFormationConservee $formationConservee) {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération des informations d'historisation.", 0, $e);
        }
        $formationConservee->setHistoDestruction($date);
        $formationConservee->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($formationConservee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0 , $e);
        }

        return $formationConservee;
    }

    /** ACCESSEUR *****************************************************************************************************/


}