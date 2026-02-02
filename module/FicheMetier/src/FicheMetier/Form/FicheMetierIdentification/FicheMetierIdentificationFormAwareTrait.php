<?php

namespace FicheMetier\Form\FicheMetierIdentification;

trait FicheMetierIdentificationFormAwareTrait
{
    private FicheMetierIdentificationForm $ficheMetierIdentificationForm;

    public function getFicheMetierIdentificationForm(): FicheMetierIdentificationForm
    {
        return $this->ficheMetierIdentificationForm;
    }

    public function setFicheMetierIdentificationForm(FicheMetierIdentificationForm $ficheMetierIdentificationForm): void
    {
        $this->ficheMetierIdentificationForm = $ficheMetierIdentificationForm;
    }


}
