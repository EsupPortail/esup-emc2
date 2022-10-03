<?php

namespace Structure\Event\InfoStructure;

use DateTime;
use Exception;
use Structure\Provider\Event\EvenementProvider;
use Structure\Service\Notification\NotificationServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;

class InfoStructureEvent  extends  EvenementService
{
    use EntityManagerAwareTrait;
    use NotificationServiceAwareTrait;
    use StructureServiceAwareTrait;

    private ?string  $deadline = null;
    public function setDeadline(string $deadline) { $this->deadline = $deadline; }

    /**
     * @param DateTime|null $dateTraitement
     * @return Evenement
     */
    public function creer(DateTime $dateTraitement = null) : Evenement
    {
        $type = $this->getTypeService()->findByCode(EvenementProvider::INFO_STRUCTURE);
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
    public function traiter(Evenement $evenement) : string
    {
        $log = "";
        $structures = $this->getStructureService()->getStructures();
        try {
            foreach ($structures as $structure) {
                $mail = $this->getNotificationService()->triggerInformations($structure);
                $log .= "Notification de la structure ". $structure->getLibelleLong() ;
                if ($mail === null) $log .= " <strong> PAS DE DESTINATAIRE </strong> ";
                $log .= "<br/>\n";
            }
        } catch(Exception $e) {
            $evenement->setLog($e->getMessage());
            return Etat::ECHEC;
        }
        $evenement->setLog($log);
        $this->update($evenement);
        return Etat::SUCCES;
    }
}