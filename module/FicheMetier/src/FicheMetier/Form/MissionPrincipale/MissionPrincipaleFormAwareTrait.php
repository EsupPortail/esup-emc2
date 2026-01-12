<?php

namespace FicheMetier\Form\MissionPrincipale;

trait MissionPrincipaleFormAwareTrait
{
    private MissionPrincipaleForm $missionPrincipaleForm;

    public function getMissionPrincipaleForm(): MissionPrincipaleForm
    {
        return $this->missionPrincipaleForm;
    }

    public function setMissionPrincipaleForm(MissionPrincipaleForm $missionPrincipaleForm): void
    {
        $this->missionPrincipaleForm = $missionPrincipaleForm;
    }
}
