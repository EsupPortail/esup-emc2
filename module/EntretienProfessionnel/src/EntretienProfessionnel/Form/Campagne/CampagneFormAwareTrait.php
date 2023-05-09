<?php

namespace EntretienProfessionnel\Form\Campagne;

trait CampagneFormAwareTrait {

    private CampagneForm $campagneForm;

    public function getCampagneForm() : CampagneForm
    {
        return $this->campagneForm;
    }

    public function setCampagneForm(CampagneForm $campagneForm) : void
    {
        $this->campagneForm = $campagneForm;
    }

}