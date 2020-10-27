<?php

namespace Formation\Controller;

use Formation\Entity\Db\FormationInstancePresence;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeServiceAwareTrait;
use Formation\Service\FormationInstancePresence\FormationInstancePresenceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FormationInstancePresenceController extends AbstractActionController {
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use FormationInstanceJourneeServiceAwareTrait;
    use FormationInstancePresenceAwareTrait;


    public function renseignerPresencesAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $presences = $this->getFormationInstancePresenceService()->getFormationInstancePresenceByInstance($instance);

        $dictionnaire = [];
        foreach ($presences as $presence) {
            $dictionnaire[$presence->getJournee()->getId()][$presence->getInscrit()->getId()] = $presence;
        }

        return new ViewModel([
            'instance' => $instance,
            'presences' => $dictionnaire,
        ]);
    }

    public function togglePresenceAction()
    {
        $journeeId = $this->params()->fromRoute('journee');
        $journee = $this->getFormationInstanceJourneeService()->getFormationInstanceJournee($journeeId);
        $inscritId = $this->params()->fromRoute('inscrit');
        $inscrit = $this->getFormationInstanceInscritService()->getFormationInstanceInscrit($inscritId);

        /** @var  FormationInstancePresence $presence */
        $presence = $this->getFormationInstancePresenceService()->getFormationInstancePresenceByJourneeAndInscrit($journee, $inscrit);
        if ($presence === null) {
            $presence = new FormationInstancePresence();
            $presence->setJournee($journee);
            $presence->setInscrit($inscrit);
            $presence->setPresent(true);
            $presence->setPresenceType("???");
            $this->getFormationInstancePresenceService()->create($presence);
        } else {
            $presence->setPresent(! $presence->isPresent());
            $this->getFormationInstancePresenceService()->update($presence);
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/reponse');
        $vm->setVariables([
            'reponse' => $presence->isPresent(),
        ]);
        return $vm;
    }

    public function togglePresencesAction()
    {
        $mode = $this->params()->fromRoute('mode');
        $inscritId = $this->params()->fromRoute('inscrit');
        $inscrit = $this->getFormationInstanceInscritService()->getFormationInstanceInscrit($inscritId);

        $instance = $inscrit->getInstance();
        $journees = $instance->getJournees();

        /** @var  FormationInstancePresence $presence */
        foreach ($journees as $journee) {
            $presence = $this->getFormationInstancePresenceService()->getFormationInstancePresenceByJourneeAndInscrit($journee, $inscrit);
            if ($presence === null) {
                $presence = new FormationInstancePresence();
                $presence->setJournee($journee);
                $presence->setInscrit($inscrit);
                $presence->setPresent($mode === 'on');
                $presence->setPresenceType("???");
                $this->getFormationInstancePresenceService()->create($presence);
            } else {
                $presence->setPresent($mode === 'on');
                $this->getFormationInstancePresenceService()->update($presence);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/reponse');
        $vm->setVariables([
            'reponse' => ($mode === 'on'),
        ]);
        return $vm;
    }
}