<?php

namespace Application\Form\FicheMetierImportation;

trait FicheMetierImportationFormAwareTrait {

    private FicheMetierImportationForm $ficheMetierImportationForm;

    /**
     * @return FicheMetierImportationForm
     */
    public function getFicheMetierImportationForm(): FicheMetierImportationForm
    {
        return $this->ficheMetierImportationForm;
    }

    /**
     * @param FicheMetierImportationForm $ficheMetierImportationForm
     */
    public function setFicheMetierImportationForm(FicheMetierImportationForm $ficheMetierImportationForm): void
    {
        $this->ficheMetierImportationForm = $ficheMetierImportationForm;
    }


}