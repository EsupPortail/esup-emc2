<?php

namespace Element\Form\Niveau;

trait NiveauFormAwareTrait {

    private NiveauForm $niveauForm;

    public function getNiveauForm(): NiveauForm
    {
        return $this->niveauForm;
    }

    public function setNiveauForm(NiveauForm $niveauForm): void
    {
        $this->niveauForm = $niveauForm;
    }
}