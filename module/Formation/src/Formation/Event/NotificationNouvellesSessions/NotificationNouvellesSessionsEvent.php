<?php

namespace Formation\Event\NotificationNouvellesSessions;


use DateTime;
use Exception;
use Formation\Provider\Event\EvenementProvider;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Formation\Service\Session\SessionServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;

class NotificationNouvellesSessionsEvent extends EvenementService
{
    use EntityManagerAwareTrait;
    use NotificationServiceAwareTrait;
    use SessionServiceAwareTrait;


    /**
     * @param DateTime|null $dateTraitement
     * @return Evenement
    */
    public function creer(DateTime $dateTraitement = null): Evenement
    {
        $type = $this->getTypeService()->findByCode(EvenementProvider::NOTIFICATION_NOUVELLES_SESSIONS);
        $etat = $this->getEtatEvenementService()->findByCode(Etat::EN_ATTENTE);

        $parametres = [
        ];

        $description = $type->getDescription();
        $evenement = $this->createEvent($description, $description, $etat, $type, $parametres, $dateTraitement);
        $this->ajouter($evenement);
        return $evenement;
    }

    /**
     * @param Evenement $evenement
     * @return string
     */
    public function traiter(Evenement $evenement): string
    {
        try {
            $sessions = $this->getSessionService()->getNouvellesSessions();
            $nbSession = count($sessions);
            if ($nbSession > 0) {
                $this->getNotificationService()->triggerNouvellesSessions($sessions);
            }
        } catch (Exception $e) {
            $evenement->setLog($e->getMessage());
            return Etat::ECHEC;
        }
        $evenement->setLog("Notification effectuée : " . $nbSession . " nouvelle·s session·s <br>");
        $this->update($evenement);
        return Etat::SUCCES;
    }
}