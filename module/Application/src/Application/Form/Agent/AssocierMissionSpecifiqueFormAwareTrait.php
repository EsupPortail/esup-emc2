<?php

namespace Application\Form\Agent;

trait AssocierMissionSpecifiqueFormAwareTrait {

    /** @var AssocierMissionSpecifiqueForm */
    private $associerMissionSpecifiqueForm;

    /**
     * @return AssocierMissionSpecifiqueForm
     */
    public function getAssocierMissionSpecifiqueForm()
    {
        return $this->associerMissionSpecifiqueForm;
    }

    /**
     * @param AssocierMissionSpecifiqueForm $associerMissionSpecifiqueForm
     * @return AssocierMissionSpecifiqueForm
     */
    public function setAssocierMissionSpecifiqueForm($associerMissionSpecifiqueForm)
    {
        $this->associerMissionSpecifiqueForm = $associerMissionSpecifiqueForm;
        return $this->associerMissionSpecifiqueForm;
    }


}