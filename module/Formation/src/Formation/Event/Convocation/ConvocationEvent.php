<?php

namespace Formation\Event\Convocation;

use DateInterval;
use DateTime;
use Exception;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Event\EvenementProvider;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;

class ConvocationEvent extends  EvenementService
{
    use EntityManagerAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use NotificationServiceAwareTrait;

    private ?string  $deadline = null;
    public function setDeadline(?string $deadline): void { $this->deadline = $deadline; }

    /**
     * @param DateTime|null $dateTraitement
     * @return Evenement
     */
    public function creer(DateTime $dateTraitement = null) : Evenement
    {
        $type = $this->getTypeService()->findByCode(EvenementProvider::CONVOCATION);
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
            $convocations = [];
            $sessions = $this->getFormationInstanceService()->getFormationsInstancesByEtat(SessionEtats::ETAT_INSCRIPTION_FERMEE);
            $deadline = (new DateTime())->sub(new DateInterval($this->deadline));
            foreach ($sessions as $session) {
                if ($session->isEvenementActive()) {
                    $dateDebut = ($session->getDebut() !== null) ? DateTime::createFromFormat('d/m/Y', $session->getDebut()) : null;
                    if ($dateDebut >= $deadline) {
                        $this->getFormationInstanceService()->envoyerConvocation($session);
                        $log .= "Convocation des inscrits de la session : " . $session->getInstanceLibelle() . "(" . $session->getInstanceCode() . ")";
                        $convocations[] = $session;
                    }
                }
            }
            $this->getNotificationService()->triggerNotifierConvocationAutomatique($convocations);
        } catch(Exception $e) {
            $evenement->setLog($e->getMessage());
            return Etat::ECHEC;
        }
        $evenement->setLog($log);
        $this->update($evenement);
        return Etat::SUCCES;
    }
}