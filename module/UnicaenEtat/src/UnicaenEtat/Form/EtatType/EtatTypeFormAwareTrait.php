<?php

namespace UnicaenEtat\Form\EtatType;

trait EtatTypeFormAwareTrait {

    /** @var EtatTypeForm */
    private $etatTypeForm;

    /**
     * @return EtatTypeForm
     */
    public function getEtatTypeForm(): EtatTypeForm
    {
        return $this->etatTypeForm;
    }

    /**
     * @param EtatTypeForm $etatTypeForm
     * @return EtatTypeForm
     */
    public function setEtatTypeForm(EtatTypeForm $etatTypeForm): EtatTypeForm
    {
        $this->etatTypeForm = $etatTypeForm;
        return $this->etatTypeForm;
    }
}