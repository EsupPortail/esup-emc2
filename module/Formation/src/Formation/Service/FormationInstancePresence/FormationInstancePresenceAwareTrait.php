<?php

namespace Formation\Service\FormationInstancePresence;

trait FormationInstancePresenceAwareTrait
{

    /** @var FormationInstancePresenceService */
    private $formationInstancePresenceService;

    /**
     * @return FormationInstancePresenceService
     */
    public function getFormationInstancePresenceService(): FormationInstancePresenceService
    {
        return $this->formationInstancePresenceService;
    }

    /**
     * @param FormationInstancePresenceService $formationInstancePresenceService
     * @return FormationInstancePresenceService
     */
    public function setFormationInstancePresenceService(FormationInstancePresenceService $formationInstancePresenceService): FormationInstancePresenceService
    {
        $this->formationInstancePresenceService = $formationInstancePresenceService;
        return $this->formationInstancePresenceService;
    }
}