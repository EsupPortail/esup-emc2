<?php

namespace Application\Service\CompetencesConservees;

use Application\Entity\Db\FicheposteCompetenceConservee;
use DateTime;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;

class CompetencesConserveesService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheposteCompetenceConservee $competenceConservee
     * @return FicheposteCompetenceConservee
     */
    public function create(FicheposteCompetenceConservee $competenceConservee) {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération des informations d'historisation.", 0, $e);
        }
        $competenceConservee->setHistoCreation($date);
        $competenceConservee->setHistoModification($date);
        $competenceConservee->setHistoCreateur($user);
        $competenceConservee->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($competenceConservee);
            $this->getEntityManager()->flush($competenceConservee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0 , $e);
        }

        return $competenceConservee;
    }

    /**
     * @param FicheposteCompetenceConservee $competenceConservee
     * @return FicheposteCompetenceConservee
     */
    public function update(FicheposteCompetenceConservee $competenceConservee) {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération des informations d'historisation.", 0, $e);
        }
        $competenceConservee->setHistoModification($date);
        $competenceConservee->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($competenceConservee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0 , $e);
        }

        return $competenceConservee;
    }

    /**
     * @param FicheposteCompetenceConservee $competenceConservee
     * @return FicheposteCompetenceConservee
     */
    public function delete(FicheposteCompetenceConservee $competenceConservee) {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération des informations d'historisation.", 0, $e);
        }
        $competenceConservee->setHistoDestruction($date);
        $competenceConservee->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($competenceConservee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0 , $e);
        }

        return $competenceConservee;
    }

    /** ACCESSEUR *****************************************************************************************************/


}