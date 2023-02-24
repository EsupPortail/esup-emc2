<?php

namespace FicheMetier\Form\FicheMetierImportation;

trait FicheMetierImportationFormAwareTrait {

    private FicheMetierImportationForm $ficheMetierImportationForm;

    public function getFicheMetierImportationForm(): FicheMetierImportationForm
    {
        return $this->ficheMetierImportationForm;
    }

    public function setFicheMetierImportationForm(FicheMetierImportationForm $ficheMetierImportationForm): void
    {
        $this->ficheMetierImportationForm = $ficheMetierImportationForm;
    }


}