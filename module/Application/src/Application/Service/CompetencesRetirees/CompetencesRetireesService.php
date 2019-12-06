<?php

namespace Application\Service\CompetencesRetirees;

use Application\Entity\Db\FicheposteCompetenceRetiree;
use DateTime;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;

class CompetencesRetireesService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheposteCompetenceRetiree $competenceRetiree
     * @return FicheposteCompetenceRetiree
     */
    public function create(FicheposteCompetenceRetiree $competenceRetiree) {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération des informations d'historisation.", 0, $e);
        }
        $competenceRetiree->setHistoCreation($date);
        $competenceRetiree->setHistoModification($date);
        $competenceRetiree->setHistoCreateur($user);
        $competenceRetiree->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($competenceRetiree);
            $this->getEntityManager()->flush($competenceRetiree);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0 , $e);
        }

        return $competenceRetiree;
    }

    /**
     * @param FicheposteCompetenceRetiree $competenceConservee
     * @return FicheposteCompetenceRetiree
     */
    public function update(FicheposteCompetenceRetiree $competenceConservee) {
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
     * @param FicheposteCompetenceRetiree $competenceConservee
     * @return FicheposteCompetenceRetiree
     */
    public function delete(FicheposteCompetenceRetiree $competenceConservee) {
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