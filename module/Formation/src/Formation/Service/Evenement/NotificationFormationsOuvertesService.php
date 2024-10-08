<?php

namespace Formation\Service\Evenement;

use DateTime;
use Exception;
use Formation\Entity\Db\Session;
use Formation\Provider\Event\EvenementProvider;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Formation\Service\Session\SessionServiceAwareTrait;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;

class NotificationFormationsOuvertesService extends EvenementService
{
    use SessionServiceAwareTrait;
    use NotificationServiceAwareTrait;

    /**
     * @param DateTime|null $dateTraitement
     * @return Evenement
     */
    public function creer(DateTime $dateTraitement = null) : Evenement
    {
        $type = $this->getTypeService()->findByCode(EvenementProvider::NOTIFICATION_FORMATION_OUVERTE);
        $etat = $this->getEtatEvenementService()->findByCode(Etat::EN_ATTENTE);

        $parametres = [
        ];

        $description = "Notification des formations 'nouvelles' ouvertes";
        $evenement = $this->createEvent($description, $description, $etat, $type, $parametres, $dateTraitement);
        $this->ajouter($evenement);
        return $evenement;
    }

    /**
     * @param Evenement $evenement
     * @return string
     */
    public function traiter(Evenement $evenement) : string
    {
//        $parametres = json_decode($evenement->getParametres(), true);

        try {
            /** @var Session[] $sessions */
            $sessions = $this->getSessionService()->getNouvellesSessions();
            $this->getNotificationService()->triggerNotificationFormationsOuvertes($sessions);
        } catch(Exception $e) {
            $evenement->setLog($e->getMessage());
            return Etat::ECHEC;
        }
        $evenement->setLog(null);
        $this->update($evenement);
        return Etat::SUCCES;
    }
}