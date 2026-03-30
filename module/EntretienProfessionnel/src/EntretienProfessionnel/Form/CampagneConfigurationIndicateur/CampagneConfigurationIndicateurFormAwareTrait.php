<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationIndicateur;

trait CampagneConfigurationIndicateurFormAwareTrait {

    private CampagneConfigurationIndicateurForm $campagneConfigurationIndicateurForm;

    public function getCampagneConfigurationIndicateurForm(): CampagneConfigurationIndicateurForm
    {
        return $this->campagneConfigurationIndicateurForm;
    }

    public function setCampagneConfigurationIndicateurForm(CampagneConfigurationIndicateurForm $campagneConfigurationIndicateurForm): void
    {
        $this->campagneConfigurationIndicateurForm = $campagneConfigurationIndicateurForm;
    }

}
