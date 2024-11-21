<?php

namespace FicheReferentiel\Form\Importation;

trait ImportationFormAwareTrait {

    private ImportationForm $importationForm;

    public function getImportationForm(): ImportationForm
    {
        return $this->importationForm;
    }

    public function setImportationForm(ImportationForm $importationForm): void
    {
        $this->importationForm = $importationForm;
    }

}