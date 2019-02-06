<?php

namespace Application\Form\FicheMetierType;

trait MissionsPrincipalesFormAwareTrait {

    /** @var MissionsPrincipalesForm $missionsPrincipalesForm */
    private $missionsPrincipalesForm;

    /**
     * @return MissionsPrincipalesForm
     */
    public function getMissionsPrincipalesForm()
    {
        return $this->missionsPrincipalesForm;
    }

    /**
     * @param MissionsPrincipalesForm $missionsPrincipalesForm
     * @return MissionsPrincipalesForm
     */
    public function setMissionsPrincipalesForm($missionsPrincipalesForm)
    {
        $this->missionsPrincipalesForm = $missionsPrincipalesForm;
        return $this->missionsPrincipalesForm;
    }


}