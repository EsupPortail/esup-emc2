<?php

namespace Carriere\Form\NiveauEnveloppe;

trait NiveauEnveloppeFormAwareTrait {

    /** @var NiveauEnveloppeForm */
    private $niveauEnveloppeForm;

    /**
     * @return NiveauEnveloppeForm
     */
    public function getNiveauEnveloppeForm(): NiveauEnveloppeForm
    {
        return $this->niveauEnveloppeForm;
    }

    /**
     * @param NiveauEnveloppeForm $niveauEnveloppeForm
     * @return NiveauEnveloppeForm
     */
    public function setNiveauEnveloppeForm(NiveauEnveloppeForm $niveauEnveloppeForm): NiveauEnveloppeForm
    {
        $this->niveauEnveloppeForm = $niveauEnveloppeForm;
        return $this->niveauEnveloppeForm;
    }

}