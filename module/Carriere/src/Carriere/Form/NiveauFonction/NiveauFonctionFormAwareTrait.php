<?php

namespace Carriere\Form\NiveauFonction;

trait NiveauFonctionFormAwareTrait
{
    private NiveauFonctionForm $niveauFonctionForm;

    public function getNiveauFonctionForm(): NiveauFonctionForm
    {
        return $this->niveauFonctionForm;
    }

    public function setNiveauFonctionForm(NiveauFonctionForm $niveauFonctionForm): void
    {
        $this->niveauFonctionForm = $niveauFonctionForm;
    }
}
