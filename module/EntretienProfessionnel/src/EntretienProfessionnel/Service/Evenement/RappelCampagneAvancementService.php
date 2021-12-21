<?php

namespace EntretienProfessionnel\Service\Evenement;

use Application\Entity\Db\Structure;
use Application\Service\Structure\StructureServiceAwareTrait;
use DateInterval;
use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use Exception;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Entity\Db\Type;
use UnicaenEvenement\Service\Evenement\EvenementService;

class RappelCampagneAvancementService extends EvenementService {
    use CampagneServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use StructureServiceAwareTrait;

    public function creer(Structure $structure, Campagne $campagne) : Evenement
    {
        /** @var Type $type */
        $type = $this->getTypeService()->findByCode(Type::RAPPEL_ENTRETIEN_PROFESSIONNEL);

        $parametres = [
            'structure'       =>  $structure->getId(),
            'campagne'       =>  $campagne->getId(),
        ];

        $dateTraitement = DateTime::createFromFormat('d/m/Y H:i:s', $campagne->getDateDebut()->format('d/m/Y'. " 08:00:00"))->add(new DateInterval($type->getRecursion()));

        $description = "Rappel de l'avancement de la campagne " . $campagne->getAnnee() . " de la structure" . $structure->getLibelleCourt();
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
            $campagne = $this->getCampagneService()->getCampagne($parametres[0]['campagne']);
            $structure = $this->getStructureService()->getStructure($parametres[0]['structure']);
            $this->getNotificationService()->triggerRappelCampagne($campagne, $structure);
        } catch(Exception $e) {
            $evenement->setLog($e->getMessage());
            return Etat::ECHEC;
        }
        $evenement->setLog(null);
        $this->update($evenement);
        return Etat::SUCCES;
    }
}