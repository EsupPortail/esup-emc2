<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationRecopie;

trait CampagneConfigurationRecopieFormAwareTrait
{

    private CampagneConfigurationRecopieForm $campagneConfigurationRecopieForm;

    public function getCampagneConfigurationRecopieForm(): CampagneConfigurationRecopieForm
    {
        return $this->campagneConfigurationRecopieForm;
    }

    public function setCampagneConfigurationRecopieForm(CampagneConfigurationRecopieForm $campagneConfigurationRecopieForm): void
    {
        $this->campagneConfigurationRecopieForm = $campagneConfigurationRecopieForm;
    }


}
