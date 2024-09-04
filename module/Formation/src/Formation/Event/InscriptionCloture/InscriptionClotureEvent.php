<?php

namespace Formation\Event\InscriptionCloture;

use DateInterval;
use DateTime;
use Exception;
use Formation\Entity\Db\Session;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Event\EvenementProvider;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Formation\Service\Session\SessionServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;

class InscriptionClotureEvent extends EvenementService
{
    use EntityManagerAwareTrait;
    use SessionServiceAwareTrait;
    use NotificationServiceAwareTrait;

    private ?string $deadline = null;

    public function setDeadline(?string $deadline): void
    {
        $this->deadline = $deadline;
    }

    /**
     * @param DateTime|null $dateTraitement
     * @return Evenement
     */
    public function creer(Session $session, DateTime $dateTraitement = null): Evenement
    {
        $type = $this->getTypeService()->findByCode(EvenementProvider::INSCRIPTION_CLOTURE);
        $etat = $this->getEtatEvenementService()->findByCode(Etat::EN_ATTENTE);

        $parametres = [
            'session'       =>  $session->getId(),
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
        $log = "";

        try {
            $parametres = json_decode($evenement->getParametres(), true);
            /** @var Session|null $session */
            $session = $this->getSessionService()->getSession($parametres['session']);

            if ($session AND $session->estNonHistorise()
                AND $session->getEtatActif() AND $session->getEtatActif()->getType()->getCode() === SessionEtats::ETAT_INSCRIPTION_OUVERTE) {
                $this->getSessionService()->fermerInscription($session);
                $log = "Session #".$session->getId()." ".$session->getInstanceLibelle()." : Inscriptions closes";
            }

        } catch (Exception $e) {
            $evenement->setLog($e->getMessage());
            return Etat::ECHEC;
        }
        $evenement->setLog($log);
        $this->update($evenement);
        return Etat::SUCCES;
    }
}