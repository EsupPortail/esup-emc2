<?php

namespace Formation\Event\RappelAgent;

use DateInterval;
use DateTime;
use Exception;
use Formation\Entity\Db\Seance;
use Formation\Entity\Db\Session;
use Formation\Provider\Event\EvenementProvider;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Formation\Service\Session\SessionServiceAwareTrait;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class RappelAgentEvent extends EvenementService
{
    use EntityManagerAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use SessionServiceAwareTrait;


    public function creer(Session $session, DateTime $dateTraitement = null): Evenement
    {
        $type = $this->getTypeService()->findByCode(EvenementProvider::RAPPEL_FORMATION_AGENT_AVANT);
        $etat = $this->getEtatEvenementService()->findByCode(Etat::EN_ATTENTE);

        $parametres = [
            'session' => $session->getId(),
        ];


        $evenement = $this->createEvent($type->getLibelle() . " (session #".$session->getId().")", $type->getDescription(), $etat, $type, $parametres, $dateTraitement);
        $this->ajouter($evenement);
        return $evenement;
    }

    public function traiter(Evenement $evenement): string
    {
        $log = "";

        try {
            $parametres = json_decode($evenement->getParametres(), true);
            /** @var Session|null $session */
            $session = $this->getSessionService()->getSession($parametres['session']);

            if ($session and $session->estNonHistorise()
                and $session->getEtatActif() and $session->estNonHistorise()) {
                $this->getNotificationService()->triggerRappelAgentAvantFormation($session);
                $log = "Session #" . $session->getId() . " " . $session->getInstanceLibelle() . " : Rappels effectués";
            }

        } catch (Exception $e) {
            $evenement->setLog($e->getMessage());
            return Etat::ECHEC;
        }
        $evenement->setLog($log);
        $this->update($evenement);
        return Etat::SUCCES;
    }

    public function updateEvent(Seance $seance): void
    {
        $session = $seance->getInstance();
        $evenements = $session->getEvenements()->toArray();
        $evenements = array_filter($evenements, function (Evenement $e) {
            return $e->getType()->getCode() === EvenementProvider::RAPPEL_FORMATION_AGENT_AVANT;
        });
        foreach ($evenements as $evenement) {
            $session->removeEvenement($evenement);
            $this->getSessionService()->update($session);
            $this->delete($evenement);
        }

        //todo calcul valeur par defaut
        $dateTraitement = null; //si on force une date on la mettra ici.
        if ($dateTraitement === null) {
            $dateDebut = $seance->getDateDebut();
            try {
                $interval = new DateInterval('P' . $this->getParametreService()->getValeurForParametre(FormationParametres::TYPE, FormationParametres::AUTO_RAPPEL) . 'D');
            } catch (Exception $e) {
                throw new RuntimeException("Un problème est survenu lors du calcul de l'interval", 0, $e);
            }
            $dateTraitement = $dateDebut->sub($interval);
        }
        if (!$dateTraitement instanceof DateTime) {
            throw new RuntimeException("La date de traitement de l'evenement [" . EvenementProvider::RAPPEL_FORMATION_AGENT_AVANT . "] n'a pas pu être déterminée.");
        }
        $event = $this->creer($session, $dateTraitement);
        $session->addEvenement($event);
        $this->getSessionService()->update($session);
    }
}