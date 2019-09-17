<?php

namespace Application\Form\RessourceRh;

trait MissionSpecifiqueThemeFormAwareTrait {

    /** @var MissionSpecifiqueThemeForm */
    private $missionSpecifiqueThemeForm;

    /**
     * @return MissionSpecifiqueThemeForm
     */
    public function getMissionSpecifiqueThemeForm()
    {
        return $this->missionSpecifiqueThemeForm;
    }

    /**
     * @param MissionSpecifiqueThemeForm $missionSpecifiqueThemeForm
     * @return MissionSpecifiqueThemeForm
     */
    public function setMissionSpecifiqueThemeForm($missionSpecifiqueThemeForm)
    {
        $this->missionSpecifiqueThemeForm = $missionSpecifiqueThemeForm;
        return $this->missionSpecifiqueThemeForm;
    }


}