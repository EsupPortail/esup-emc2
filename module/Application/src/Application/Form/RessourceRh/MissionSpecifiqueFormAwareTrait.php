<?php

namespace Application\Form\RessourceRh;

trait MissionSpecifiqueFormAwareTrait {

    /** @var MissionSpecifiqueForm */
    private $missionSpecifiqueForm;

    /**
     * @return MissionSpecifiqueForm
     */
    public function getMissionSpecifiqueForm()
    {
        return $this->missionSpecifiqueForm;
    }

    /**
     * @param MissionSpecifiqueForm $missionSpecifiqueForm
     * @return MissionSpecifiqueForm
     */
    public function setMissionSpecifiqueForm($missionSpecifiqueForm)
    {
        $this->missionSpecifiqueForm = $missionSpecifiqueForm;
        return $this->missionSpecifiqueForm;
    }


}