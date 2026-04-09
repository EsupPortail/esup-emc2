<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationPresaisie;

trait CampagneConfigurationPresaisieFormAwareTrait
{

    private CampagneConfigurationPresaisieForm $campagneConfigurationPresaisieForm;

    public function getCampagneConfigurationPresaisieForm(): CampagneConfigurationPresaisieForm
    {
        return $this->campagneConfigurationPresaisieForm;
    }

    public function setCampagneConfigurationPresaisieForm(CampagneConfigurationPresaisieForm $campagneConfigurationPresaisieForm): void
    {
        $this->campagneConfigurationPresaisieForm = $campagneConfigurationPresaisieForm;
    }


}
