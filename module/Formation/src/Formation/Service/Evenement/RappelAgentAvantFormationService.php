<?php

namespace Formation\Service\Evenement;

use DateTime;
use Exception;
use Formation\Entity\Db\FormationInstance;
use Formation\Provider\EvenementProvider;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;

class RappelAgentAvantFormationService extends EvenementService
{
    use EntityManagerAwareTrait;
    use NotificationServiceAwareTrait;

    /**
     * @param FormationInstance $instance
     * @param DateTime|null $dateTraitement
     * @return Evenement
     */
    public function creer(FormationInstance $instance, DateTime $dateTraitement = null) : Evenement
    {
        $type = $this->getTypeService()->findByCode(EvenementProvider::RAPPEL_FORMATION_AGENT_AVANT);
        $etat = $this->getEtatEvenementService()->findByCode(Etat::EN_ATTENTE);

        $parametres = [
            'instance'       =>  $instance->getId(),
        ];

        $description = "Rappel des agents avant la instance de formation #" . $instance->getId();
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
        $parametres = json_decode($evenement->getParametres(), true);

        try {
            $instance = $this->getEntityManager()->getRepository(FormationInstance::class)->find($parametres['instance']);
            $this->getNotificationService()->triggerRappelAgentAvantFormation($instance);
        } catch(Exception $e) {
            $evenement->setLog($e->getMessage());
            return Etat::ECHEC;
        }
        $evenement->setLog(null);
        $this->update($evenement);
        return Etat::SUCCES;
    }
}