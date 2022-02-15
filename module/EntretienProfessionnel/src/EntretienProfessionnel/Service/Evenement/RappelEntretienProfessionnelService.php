<?php

namespace EntretienProfessionnel\Service\Evenement;

use DateTime;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Provider\EvenementProvider;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use Exception;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Entity\Db\Type;
use UnicaenEvenement\Service\Etat\EtatServiceAwareTrait;
use UnicaenEvenement\Service\Evenement\EvenementService;

class RappelEntretienProfessionnelService extends EvenementService {

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
        $type = $this->getTypeService()->findByCode(EvenementProvider::RAPPEL_ENTRETIEN_PROFESSIONNEL);

        $parametres = [
            'entretien'       =>  $entretien->getId(),
        ];

        $description = "Rappel de l'entretien professionnel #" . $entretien->getId() . " de " . $entretien->getAgent()->getDenomination();
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
            $this->getNotificationService()->triggerRappelEntretien($entretien);
        } catch(Exception $e) {
            $evenement->setLog($e->getMessage());
            return Etat::ECHEC;
        }
        $evenement->setLog(null);
        $this->update($evenement);
        return Etat::SUCCES;
    }


}