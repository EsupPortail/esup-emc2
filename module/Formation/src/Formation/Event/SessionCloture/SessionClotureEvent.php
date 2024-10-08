<?php

namespace Formation\Event\SessionCloture;

use DateInterval;
use DateTime;
use Exception;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Event\EvenementProvider;
use Formation\Service\Session\SessionServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;

class SessionClotureEvent extends EvenementService
{
    use EntityManagerAwareTrait;
    use SessionServiceAwareTrait;

    private ?string $deadline = null;

    public function setDeadline(?string $deadline): void
    {
        $this->deadline = $deadline;
    }

    /**
     * @param DateTime|null $dateTraitement
     * @return Evenement
     */
    public function creer(DateTime $dateTraitement = null): Evenement
    {
        $type = $this->getTypeService()->findByCode(EvenementProvider::SESSION_CLOTURE);
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
    public function traiter(Evenement $evenement): string
    {
        $log = "";
        $nbSession = 0;
        try {
            $sessions = $this->getSessionService()->getSessionsByEtat(SessionEtats::ETAT_ATTENTE_RETOURS);
            $deadline = (new DateTime())->sub(new DateInterval($this->deadline));
            foreach ($sessions as $session) {
                if ($session->isEvenementActive()) {
                    $dateFin = ($session->getFin() !== null) ? DateTime::createFromFormat('d/m/Y', $session->getFin()) : null;
                    if ($dateFin < $deadline) {
                        $this->getSessionService()->cloturer($session);
                        $log .= "Traitement de la session " . $session->getInstanceCode() . " " . $session->getInstanceLibelle() . "<br>";
                        $nbSession++;
                    }
                }
            }
        } catch (Exception $e) {
            $evenement->setLog($e->getMessage());
            return Etat::ECHEC;
        }
        $evenement->setLog($nbSession . " session·s traitée·s <br>" . $log);
        $this->update($evenement);
        return Etat::SUCCES;
    }
}