<?php

namespace Carriere\Form\NiveauEnveloppe;

trait NiveauEnveloppeFormAwareTrait {

    private NiveauEnveloppeForm $niveauEnveloppeForm;

    public function getNiveauEnveloppeForm(): NiveauEnveloppeForm
    {
        return $this->niveauEnveloppeForm;
    }

    public function setNiveauEnveloppeForm(NiveauEnveloppeForm $niveauEnveloppeForm): void
    {
        $this->niveauEnveloppeForm = $niveauEnveloppeForm;
    }

}