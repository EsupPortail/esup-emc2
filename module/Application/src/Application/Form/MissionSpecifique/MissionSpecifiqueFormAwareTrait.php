<?php

namespace Application\Form\MissionSpecifique;

trait MissionSpecifiqueFormAwareTrait {

    /** @var MissionSpecifiqueForm */
    private $missionSpecifiqueForm;

    /**
     * @return MissionSpecifiqueForm
     */
    public function getMissionSpecifiqueForm() : MissionSpecifiqueForm
    {
        return $this->missionSpecifiqueForm;
    }

    /**
     * @param MissionSpecifiqueForm $missionSpecifiqueForm
     * @return MissionSpecifiqueForm
     */
    public function setMissionSpecifiqueForm(MissionSpecifiqueForm $missionSpecifiqueForm) : MissionSpecifiqueForm
    {
        $this->missionSpecifiqueForm = $missionSpecifiqueForm;
        return $this->missionSpecifiqueForm;
    }


}