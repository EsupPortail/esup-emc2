<?php

namespace EntretienProfessionnel\Service\Evenement;

use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use DateInterval;
use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use Exception;
use RuntimeException;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;

class RappelCampagneAvancementAutoriteService extends EvenementService {
    use AgentAutoriteServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use StructureServiceAwareTrait;

    public function creer(Campagne $campagne, ?DateTime $dateTraitement = null) : Evenement
    {
       $parametres = [
            'campagne'       =>  $campagne->getId(),
        ];

        try {
            if ($dateTraitement === null) {
                $dateTraitement = DateTime::createFromFormat('d/m/Y H:i:s', $campagne->getDateDebut()->format('d/m/Y' . " 09:00:00"));
                if ($this->getType()->getRecursion() !== null) $dateTraitement->add(new DateInterval($this->getType()->getRecursion()));
            }
        } catch (Exception $e) {
            throw new RuntimeException("Problème de calcul de la date de traitement de l'événement",0 ,$e);
        }

        $description = "Rappel de l'avancement de la campagne " . $campagne->getAnnee() . " [Autorité hiérarchique] ";
        $evenement = $this->createEvent($description, $description, $this->getEtatEvenementService()->findByCode(Etat::EN_ATTENTE), $this->getType(), $parametres, $dateTraitement);
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
        $campagne = $this->getCampagneService()->getCampagne($parametres['campagne']);

        if ($campagne === null) {
            $evenement->setLog("Campagne #". $parametres['campagne'] . " non trouvée.");
            return Etat::ECHEC;
        }

        $message = "";

        // AUTORITES
        $message .= "<strong>Expédition vers les autorités hiérarchiques</strong><br>";
        try {
            $autorites = $this->getAgentAutoriteService()->getAgentsAutorites();
            $dictionnaire  = [];
            foreach ($autorites as $autorite) $dictionnaire[$autorite->getAutorite()->getId()] = $autorite;
            foreach ($dictionnaire as $autorite) {
                $completed = true;
                $agents = $this->getAgentAutoriteService()->getAgentsAutoritesByAutorite($autorite->getAutorite());
                foreach ($agents as $agent) {
                    $ep = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByAgentAndCampagne($agent->getAgent(), $campagne);
                    if ($ep === null || !$ep->isEtatActif(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT)) {
                        $completed = false;
                        break;
                    }
                }
                if (!$completed) {
                    try {
                        $this->getNotificationService()->triggerRappelCampagneAutorite($campagne, $autorite->getAutorite());
                        $message .= "Notification faites vers " . $autorite->getAutorite()->getDenomination() . "<br/>\n";
                    } catch (Exception $e ) {
                        $message .= "<span class='text-danger'>Notification impossible vers ".$autorite->getSuperieur()->getDenomination()."</span><br/>\n";
                    }
                }
            }
        } catch(Exception $e) {
            $evenement->setLog($message . $e->getMessage());
            return Etat::ECHEC;
        }

        $evenement->setLog($message);
        $this->update($evenement);
        return Etat::SUCCES;
    }
}