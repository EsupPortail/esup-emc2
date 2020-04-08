<?php

namespace Application\Service\ApplicationsRetirees;

use Application\Entity\Db\FicheposteApplicationRetiree;
use DateTime;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class ApplicationsRetireesService {
    use DateTimeAwareTrait;
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheposteApplicationRetiree $applicationConservee
     * @return FicheposteApplicationRetiree
     */
    public function create(FicheposteApplicationRetiree $applicationConservee)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

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
     * @param FicheposteApplicationRetiree $applicationConservee
     * @return FicheposteApplicationRetiree
     */
    public function update(FicheposteApplicationRetiree $applicationConservee)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

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
     * @param FicheposteApplicationRetiree $applicationConservee
     * @return FicheposteApplicationRetiree
     */
    public function delete(FicheposteApplicationRetiree $applicationConservee)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

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