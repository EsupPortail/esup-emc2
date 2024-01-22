<?php

namespace EntretienProfessionnel\Service\Evenement;

use DateTime;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Event\EvenementProvider;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use Exception;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Etat\EtatServiceAwareTrait;
use UnicaenEvenement\Service\Evenement\EvenementService;

class RappelPasObservationService extends EvenementService {

    use EntretienProfessionnelServiceAwareTrait;
    use EtatServiceAwareTrait;
    use NotificationServiceAwareTrait;

    /**
     * @param EntretienProfessionnel $entretien
     * @param DateTime|null $dateTraitement
     * @return Evenement
     */
    public function creer(EntretienProfessionnel $entretien, DateTime $dateTraitement = null) : Evenement
    {
        $type = $this->getTypeService()->findByCode(EvenementProvider::RAPPEL_PAS_OBSERVATION_ENTRETIEN_PROFESSIONNEL);

        $parametres = [
            'entretien'       =>  $entretien->getId(),
        ];

        $description = "Rappel ''Pas d'Observation'' de l'entretien professionnel #" . $entretien->getId() . " de " . $entretien->getAgent()->getDenomination();
        $evenement = $this->createEvent($description, $description, $this->getEtatEvenementService()->findByCode(Etat::EN_ATTENTE), $type, $parametres, $dateTraitement);
        $this->ajouter($evenement);
        return $evenement;
    }

    /**
     * @param Evenement $evenement
     * @return string
     */
    public function traiter(Evenement $evenement) : string
    {
        $parametres = json_decode($evenement->getParametres(), true);

        try {
            $entretien = $this->getEntretienProfessionnelService()->getEntretienProfessionnel($parametres['entretien']);
            if (!isset($entretien)) {
                $evenement->setLog("Plus d'entretien professionnel");
                return Etat::ECHEC;
            }
            if ($entretien->isEtatActif(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE)) {
                $this->getNotificationService()->triggerPasObservations($entretien);
                $evenement->setLog('Notification effectuée');
            } else {
                $evenement->setLog('Des observations ont été faites, pas de notification');
            }
        } catch(Exception $e) {
            $evenement->setLog($e->getMessage());
            return Etat::ECHEC;
        }

        $this->update($evenement);
        return Etat::SUCCES;
    }


}