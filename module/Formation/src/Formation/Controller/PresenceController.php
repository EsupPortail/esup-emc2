<?php

namespace Formation\Controller;

use Formation\Entity\Db\Presence;
use Formation\Service\Inscription\InscriptionServiceAwareTrait;
use Formation\Service\Presence\PresenceServiceAwareTrait;
use Formation\Service\Seance\SeanceServiceAwareTrait;
use Formation\Service\Session\SessionServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class PresenceController extends AbstractActionController
{
    use SessionServiceAwareTrait;
    use InscriptionServiceAwareTrait;
    use PresenceServiceAwareTrait;
    use SeanceServiceAwareTrait;


    public function renseignerPresencesAction(): ViewModel
    {
        $session = $this->getSessionService()->getRequestedSession($this);
        $presences = $this->getPresenceService()->getPresenceBySession($session);

        $dictionnaire = [];
        foreach ($presences as $presence) {
            $dictionnaire[$presence->getJournee()->getId()][$presence->getInscription()->getId()] = $presence;
        }

        return new ViewModel([
            'session' => $session,
            'presences' => $dictionnaire,
        ]);
    }

    public function togglePresenceAction(): ViewModel
    {
        $journeeId = $this->params()->fromRoute('journee');
        $journee = $this->getSeanceService()->getSeance($journeeId);
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        /** @var  Presence $presence */
        $presence = $this->getPresenceService()->getPresenceByJourneeAndInscription($journee, $inscription);
        if ($presence === null) {
            $presence = new Presence();
            $presence->setJournee($journee);
            $presence->setInscription($inscription);
            $presence->setStatut(Presence::PRESENCE_PRESENCE);
            $presence->setPresenceType("???");
            $this->getPresenceService()->create($presence);
        } else {
            $presence->tooglePresence();
            $this->getPresenceService()->update($presence);
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/reponse');
        $vm->setVariables([
            'reponse' => $presence->getStatut(),
        ]);
        return $vm;
    }

    public function togglePresencesAction(): ViewModel
    {
        $mode = $this->params()->fromRoute('mode');
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $session = $inscription->getSession();
        $journees = $session->getSeances();

        foreach ($journees as $journee) {
            $presence = $this->getPresenceService()->getPresenceByJourneeAndInscription($journee, $inscription);
            if ($presence === null) {
                $this->getPresenceService()->createWith($inscription, $journee, $mode);
            } else {
                $presence->setStatut($mode);
                $this->getPresenceService()->update($presence);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/reponse');
        $vm->setVariables([
            'reponse' => $mode,
        ]);
        return $vm;
    }

    public function toggleToutesPresencesAction(): ViewModel
    {
        $mode = $this->params()->fromRoute('mode');
        $session = $this->getSessionService()->getRequestedSession($this);

        foreach ($session->getInscriptions() as $inscription) {
            $journees = $session->getSeances();
            foreach ($journees as $journee) {
                $presence = $this->getPresenceService()->getPresenceByJourneeAndInscription($journee, $inscription);
                if ($presence === null) {
                    $this->getPresenceService()->createWith($inscription, $journee, $mode);
                } else {
                    $presence->setStatut($mode);
                    $this->getPresenceService()->update($presence);
                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/reponse');
        $vm->setVariables([
            'reponse' => $mode,
        ]);
        return $vm;
    }
}