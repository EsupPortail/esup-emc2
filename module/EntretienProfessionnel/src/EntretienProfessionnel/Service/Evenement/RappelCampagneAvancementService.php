<?php

namespace EntretienProfessionnel\Service\Evenement;

use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use DateInterval;
use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use Exception;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;

class RappelCampagneAvancementService extends EvenementService {
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use CampagneServiceAwareTrait;
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

        $description = "Rappel de l'avancement de la campagne " . $campagne->getAnnee();
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

        $message = "";

        // AUTORITES
        $message .= "<strong>Expédition vers les autorités hiérarchiques</strong><br>";
        try {
            $autorites = $this->getAgentAutoriteService()->getAgentsAutorites(false, 'id','ASC');
            $dictionnaire  = [];
            foreach ($autorites as $autorite) $dictionnaire[$autorite->getAutorite()->getId()] = $autorite;
            foreach ($dictionnaire as $autorite) {
                $this->getNotificationService()->triggerRappelCampagneAutorite($campagne, $autorite->getAutorite());
                $message .= "Notification faites vers " . $autorite->getAutorite()->getDenomination() . "<br/>\n";
            }
        } catch(Exception $e) {
            $evenement->setLog($message . $e->getMessage());
            return Etat::ECHEC;
        }

        // SUPERIEURS
        $message .= "<strong>Expédition vers les supérieurs hiérarchiques</strong><br>";
        try {
            $superieurs = $this->getAgentSuperieurService()->getAgentsSuperieurs(false, 'id', 'ASC');
            $dictionnaire  = [];
            foreach ($superieurs as $superieur) $dictionnaire[$superieur->getSuperieur()->getId()] = $superieur;
            foreach ($dictionnaire as $superieur) {
                $this->getNotificationService()->triggerRappelCampagneSuperieur($campagne, $superieur->getSuperieur());
                $message .= "Notification faites vers " . $superieur->getSuperieur()->getDenomination() . "<br/>\n";
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