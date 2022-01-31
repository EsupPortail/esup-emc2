<?php

namespace EntretienProfessionnel\Service\Evenement;

use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use DateInterval;
use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;

class RappelCampagneAvancementService extends EvenementService {
    use AgentServiceAwareTrait;
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
        $structures = $this->getStructureService()->getStructures();

        $message = "";

        try {
            foreach ($structures as $structure) {
                $structures = $this->getStructureService()->getStructuresFilles($structure); $structures[] = $structure;
                $agents = $this->getAgentService()->getAgentsByStructures($structures);
                if (!empty($agents)) {
                    if (!empty($structure->getResponsables())) {
                        $this->getNotificationService()->triggerRappelCampagne($campagne, $structure);
                        $message .= "Notification faites vers " . $structure->getLibelleLong() . "<br/>\n";
                    } else {
                        $message .= "<span style='color:darkred;'>Pas de responsable pour le structure " . $structure->getLibelleLong() . "</span><br/>\n";
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