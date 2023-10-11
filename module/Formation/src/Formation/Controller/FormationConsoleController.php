<?php

namespace Formation\Controller;

use DateInterval;
use DateTime;
use Exception;
use Formation\Provider\Etat\SessionEtats;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

/**
 * Controleur pour gérer les routes 'console' du module de formation
 *
 */
class FormationConsoleController extends AbstractActionController
{
    use FormationInstanceServiceAwareTrait;
    use ParametreServiceAwareTrait;

    /**
     * @throws Exception
     */
    public function gererFormationsAction(): void
    {
        $this->notifierConvocationAction();
        $this->notifierQuestionnaireAction();
        $this->cloturerSessionsAction();
    }

    /** Notifie les convocations et passe la session a débuté
     * @throws Exception
     */
    public function notifierConvocationAction(): void
    {
        echo (new DateTime())->format('d/m/y à H:i:s') . "\n";
        echo "Notifier Convocation" . "\n";

        $delai = $this->getParametreService()->getParametreByCode('FORMATION', 'INTERVAL_CONVOCATION');
        if ($delai === null) throw new Exception("Le parametre FORMATION\INTERVAL_CONVOCATION n'est pas déclaré ! ");
        if ($delai->getValeur() == "") throw new Exception("Le parametre FORMATION\INTERVAL_CONVOCATION n'a pas de valeur ! ");

        $now = new DateTime();
        $now->add(new DateInterval('P' . $delai->getValeur() . 'D'));
        $sessions = $this->getFormationInstanceService()->getFormationsInstancesByEtat(SessionEtats::ETAT_INSCRIPTION_FERMEE);
        foreach ($sessions as $session) {
            $debut = DateTime::createFromFormat('d/m/Y', $session->getDebut());
            if ($debut <= $now) {
                $this->getFormationInstanceService()->envoyerConvocation($session);
                echo (new DateTime())->format('d/m/y à H:i:s') . "\n";
                echo "Envoi des convocations effectué pour la session de formation " . $session->getFormation()->getLibelle() . " - " . $session->getId() . "\n";
                $this->getFormationInstanceService()->envoyerEmargement($session);
                echo (new DateTime())->format('d/m/y à H:i:s') . "\n";
                echo "Envoi des émargements effectué pour la session de formation " . $session->getFormation()->getLibelle() . " - " . $session->getId() . "\n";
            }

        }
    }

    /**
     * @throws Exception
     */
    public function notifierQuestionnaireAction(): void
    {
        echo (new DateTime())->format('d/m/y à H:i:s') . "\n";
        echo "Notifier Questionnaire" . "\n";

        $delai = $this->getParametreService()->getParametreByCode('FORMATION', 'INTERVAL_QUESTIONNAIRE');
        if ($delai === null) throw new Exception("Le parametre FORMATION\INTERVAL_QUESTIONNAIRE n'est pas déclaré ! ");
        if ($delai->getValeur() == "") throw new Exception("Le parametre FORMATION\INTERVAL_QUESTIONNAIRE n'a pas de valeur ! ");

        $now = new DateTime();
        $now->add(new DateInterval('P' . $delai->getValeur() . 'D'));
        $sessions = $this->getFormationInstanceService()->getFormationsInstancesByEtat(SessionEtats::ETAT_FORMATION_CONVOCATION);
        foreach ($sessions as $session) {
            $fin = DateTime::createFromFormat('d/m/Y', $session->getFin());
            if ($fin <= $now) {
                $this->getFormationInstanceService()->demanderRetour($session);
                echo (new DateTime())->format('d/m/y à H:i:s') . "\n";
                echo "Envoi des demandes de retour effectué pour la session de formation " . $session->getFormation()->getLibelle() . " - " . $session->getId() . "\n";
            }
        }
    }

    /**
     * @throws Exception
     */
    public function cloturerSessionsAction(): void
    {
        echo (new DateTime())->format('d/m/y à H:i:s') . "\n";
        echo "Clotûre des sessions" . "\n";

        $delai = $this->getParametreService()->getParametreByCode('FORMATION', 'INTERVAL_CLOTURE');
        if ($delai === null) throw new Exception("Le parametre FORMATION\INTERVAL_CLOTURE n'est pas déclaré ! ");
        if ($delai->getValeur() == "") throw new Exception("Le parametre FORMATION\INTERVAL_CLOTURE n'a pas de valeur ! ");

        $now = new DateTime();
        $now->add(new DateInterval('P' . $delai->getValeur() . 'D'));
        $sessions = $this->getFormationInstanceService()->getFormationsInstancesByEtat(SessionEtats::ETAT_ATTENTE_RETOURS);
        foreach ($sessions as $session) {
            $fin = DateTime::createFromFormat('d/m/Y', $session->getFin());
            if ($fin <= $now) {
                $this->getFormationInstanceService()->cloturer($session);
                echo (new DateTime())->format('d/m/y à H:i:s') . "\n";
                echo "Clotûre de la la session de formation " . $session->getFormation()->getLibelle() . " - " . $session->getId() . "\n";
            }
        }
    }
}