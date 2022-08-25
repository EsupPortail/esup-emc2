<?php

namespace Formation\Event\InscriptionCloture;

use DateInterval;
use DateTime;
use Exception;
use Formation\Entity\Db\FormationInstance;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\EvenementProvider;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;

class InscriptionClotureEvent extends  EvenementService
{
    use EntityManagerAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use NotificationServiceAwareTrait;

    const DEADLINE = 'P2W';
    /**
     * @param DateTime|null $dateTraitement
     * @return Evenement
     */
    public function creer(DateTime $dateTraitement = null) : Evenement
    {
        $type = $this->getTypeService()->findByCode(EvenementProvider::INSCRIPTION_CLOTURE);
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

        try {
            $closes = [];
            $sessions = $this->getFormationInstanceService()->getFormationsInstancesByEtat(SessionEtats::ETAT_INSCRIPTION_OUVERTE);
            $deadline = (new DateTime())->sub(new DateInterval(self::DEADLINE));
            foreach ($sessions as $session) {
                $dateDebut = ($session->getDebut() !== null)?DateTime::createFromFormat('d/m/Y', $session->getDebut()):null;
                if ($dateDebut >= $deadline) {
                    $this->getFormationInstanceService()->fermerInscription($session);
                    $log .= "Fermeture des inscriptions de la session : " . $session->getInstanceLibelle() . "(". $session->getInstanceCode().")";
                    $closes[] = $session;
                }
            }
            $this->getNotificationService()->triggerNotifierInscriptionClotureAutomatique($closes);
        } catch(Exception $e) {
            $evenement->setLog($e->getMessage());
            return Etat::ECHEC;
        }
        $evenement->setLog($log);
        $this->update($evenement);
        return Etat::SUCCES;
    }
}