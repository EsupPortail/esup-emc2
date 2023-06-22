<?php

namespace Formation\Controller;

use Formation\Entity\Db\Presence;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Formation\Service\Presence\PresenceAwareTrait;
use Formation\Service\Seance\SeanceServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class PresenceController extends AbstractActionController
{
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use PresenceAwareTrait;
    use SeanceServiceAwareTrait;


    public function renseignerPresencesAction() : ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $presences = $this->getPresenceService()->getPresenceByInstance($instance);

        $dictionnaire = [];
        foreach ($presences as $presence) {
            $dictionnaire[$presence->getJournee()->getId()][$presence->getInscrit()->getId()] = $presence;
        }

        return new ViewModel([
            'instance' => $instance,
            'presences' => $dictionnaire,
        ]);
    }

    public function togglePresenceAction() : ViewModel
    {
        $journeeId = $this->params()->fromRoute('journee');
        $journee = $this->getSeanceService()->getSeance($journeeId);
        $inscritId = $this->params()->fromRoute('inscrit');
        $inscrit = $this->getFormationInstanceInscritService()->getFormationInstanceInscrit($inscritId);

        /** @var  Presence $presence */
        $presence = $this->getPresenceService()->getPresenceByJourneeAndInscrit($journee, $inscrit);
        if ($presence === null) {
            $presence = new Presence();
            $presence->setJournee($journee);
            $presence->setInscrit($inscrit);
            $presence->setStatut(Presence::PRESENCE_PRESENCE);
            $presence->setPresenceType("???");
            $this->getPresenceService()->create($presence);
        } else {
            $presence->tooglePresence();
            $this->getPresenceService()->update($presence);
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/reponse');
        $vm->setVariables([
            'reponse' => $presence->getStatut(),
        ]);
        return $vm;
    }

    public function togglePresencesAction() : ViewModel
    {
        $mode = $this->params()->fromRoute('mode');
        $inscritId = $this->params()->fromRoute('inscrit');
        $inscrit = $this->getFormationInstanceInscritService()->getFormationInstanceInscrit($inscritId);

        $instance = $inscrit->getInstance();
        $journees = $instance->getJournees();

        /** @var  Presence $presence */
        foreach ($journees as $journee) {
            $presence = $this->getPresenceService()->getPresenceByJourneeAndInscrit($journee, $inscrit);
            if ($presence === null) {
                $presence = new Presence();
                $presence->setJournee($journee);
                $presence->setInscrit($inscrit);
                $presence->setStatut($mode);
                $presence->setPresenceType("???");
                $this->getPresenceService()->create($presence);
            } else {
                $presence->setStatut($mode);
                $this->getPresenceService()->update($presence);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/reponse');
        $vm->setVariables([
            'reponse' => $mode,
        ]);
        return $vm;
    }

    public function toggleToutesPresencesAction() : ViewModel
    {
        $mode = $this->params()->fromRoute('mode');
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        foreach ($instance->getInscrits() as $inscrit) {
            $journees = $instance->getJournees();
            /** @var  Presence $presence */
            foreach ($journees as $journee) {
                $presence = $this->getPresenceService()->getPresenceByJourneeAndInscrit($journee, $inscrit);
                if ($presence === null) {
                    $presence = new Presence();
                    $presence->setJournee($journee);
                    $presence->setInscrit($inscrit);
                    $presence->setStatut($mode);
                    $presence->setPresenceType("???");
                    $this->getPresenceService()->create($presence);
                } else {
                    $presence->setStatut($mode);
                    $this->getPresenceService()->update($presence);
                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/reponse');
        $vm->setVariables([
            'reponse' => $mode,
        ]);
        return $vm;
    }
}