<?php

namespace Application\Form\RessourceRh;

trait MissionSpecifiqueTypeFormAwareTrait {

    /** @var MissionSpecifiqueTypeForm */
    private $missionSpecifiqueTypeForm;

    /**
     * @return MissionSpecifiqueTypeForm
     */
    public function getMissionSpecifiqueTypeForm()
    {
        return $this->missionSpecifiqueTypeForm;
    }

    /**
     * @param MissionSpecifiqueTypeForm $missionSpecifiqueTypeForm
     * @return MissionSpecifiqueTypeForm
     */
    public function setMissionSpecifiqueTypeForm($missionSpecifiqueTypeForm)
    {
        $this->missionSpecifiqueTypeForm = $missionSpecifiqueTypeForm;
        return $this->missionSpecifiqueTypeForm;
    }


}