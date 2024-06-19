<?php

namespace EntretienProfessionnel\Service\Evenement;

use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use DateInterval;
use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use Exception;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;

class RappelCampagneAvancementSuperieurService extends EvenementService {
    use AgentSuperieurServiceAwareTrait;
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

        $description = "Rappel de l'avancement de la campagne " . $campagne->getAnnee() . " [Supérieur·e hiérachique]";
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

        // SUPERIEURS
        $message .= "<strong>Expédition vers les supérieurs hiérarchiques</strong><br>";
        try {
            $superieurs = $this->getAgentSuperieurService()->getAgentsSuperieurs(false, 'id', 'ASC');
            $dictionnaire  = [];
            foreach ($superieurs as $superieur) $dictionnaire[$superieur->getSuperieur()->getId()] = $superieur;
            foreach ($dictionnaire as $superieur) {
                $completed = true;
                $agents = $this->getAgentSuperieurService()->getAgentsSuperieursBySuperieur($superieur->getSuperieur());
                foreach ($agents as $agent) {
                    $ep = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByAgentAndCampagne($agent->getAgent(), $campagne);
                    if ($ep === null || !$ep->isEtatActif(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT)) {
                        $completed = false;
                        break;
                    }
                }
                if (!$completed) {
                    try {
                        $this->getNotificationService()->triggerRappelCampagneSuperieur($campagne, $superieur->getSuperieur());
                        $message .= "Notification faites vers " . $superieur->getSuperieur()->getDenomination() . "<br/>\n";
                    } catch (\Laminas\Mail\Protocol\Exception\RuntimeException $e ) {
                        $message .= "<span class='text-danger'>Notification impossible vers ".$superieur->getSuperieur()->getDenomination()."</span><br/>\n";
                    };
                }
            }
        } catch(Exception $e) {
            $evenement->setLog($message . "(".$e->getCode()."|".get_class($e).")".$e->getMessage());
            return Etat::ECHEC;
        }

        $evenement->setLog($message);
        $this->update($evenement);
        return Etat::SUCCES;
    }
}