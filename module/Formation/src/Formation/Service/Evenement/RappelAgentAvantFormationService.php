<?php

namespace Formation\Service\Evenement;

use DateTime;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Exception;
use Formation\Entity\Db\Session;
use Formation\Provider\Event\EvenementProvider;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;

class RappelAgentAvantFormationService extends EvenementService
{
    use ProvidesObjectManager;
    use NotificationServiceAwareTrait;

    /**
     * @param Session $session
     * @param DateTime|null $dateTraitement
     * @return Evenement
     */
    public function creer(Session $session, DateTime $dateTraitement = null) : Evenement
    {
        $type = $this->getTypeService()->findByCode(EvenementProvider::RAPPEL_FORMATION_AGENT_AVANT);
        $etat = $this->getEtatEvenementService()->findByCode(Etat::EN_ATTENTE);

        $parametres = [
            'instance'       =>  $session->getId(),
        ];

        $description = "Rappel des agents avant la instance de formation #" . $session->getId();
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
            /** @var Session|null $session */
            $session = $this->getObjectManager()->getRepository(Session::class)->find($parametres['instance']);
            if ($session !== null) $this->getNotificationService()->triggerRappelAgentAvantFormation($session);
        } catch(Exception $e) {
            $evenement->setLog($e->getMessage());
            $this->update($evenement);
            return Etat::ECHEC;
        }
        $evenement->setLog(null);
        $this->update($evenement);
        return Etat::SUCCES;
    }
}