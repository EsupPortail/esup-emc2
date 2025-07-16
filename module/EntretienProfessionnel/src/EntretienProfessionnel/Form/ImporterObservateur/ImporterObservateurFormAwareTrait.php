<?php

namespace EntretienProfessionnel\Form\ImporterObservateur;

trait ImporterObservateurFormAwareTrait {

    private ImporterObservateurForm $importerObservateurForm;

    public function getImporterObservateurForm(): ImporterObservateurForm
    {
        return $this->importerObservateurForm;
    }

    public function setImporterObservateurForm(ImporterObservateurForm $importerObservateurForm): void
    {
        $this->importerObservateurForm = $importerObservateurForm;
    }

}