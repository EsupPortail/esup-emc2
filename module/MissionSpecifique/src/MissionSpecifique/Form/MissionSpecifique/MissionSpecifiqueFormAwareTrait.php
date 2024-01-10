<?php

namespace MissionSpecifique\Form\MissionSpecifique;

trait MissionSpecifiqueFormAwareTrait {

    private MissionSpecifiqueForm $missionSpecifiqueForm;

    public function getMissionSpecifiqueForm() : MissionSpecifiqueForm
    {
        return $this->missionSpecifiqueForm;
    }

    public function setMissionSpecifiqueForm(MissionSpecifiqueForm $missionSpecifiqueForm) : void
    {
        $this->missionSpecifiqueForm = $missionSpecifiqueForm;
    }

}