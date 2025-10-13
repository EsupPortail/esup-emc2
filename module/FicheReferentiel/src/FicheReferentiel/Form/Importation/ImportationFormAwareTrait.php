<?php

namespace FicheReferentiel\Form\Importation;

/**
 * ATTENTION
 * La partie fichier ne marche que si on précise des chose dans la route de retour
 * exemple : $form->setAttribute('action', $this->url()->fromRoute('mission-principale/importer', ['mode' => 'preview', 'path' => null], [], true));
 */
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