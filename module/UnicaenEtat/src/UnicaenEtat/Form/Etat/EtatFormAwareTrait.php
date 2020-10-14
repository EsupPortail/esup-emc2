<?php

namespace UnicaenEtat\Form\Etat;

trait EtatFormAwareTrait {

    /** @var EtatForm */
    private $etatForm;

    /**
     * @return EtatForm
     */
    public function getEtatForm(): EtatForm
    {
        return $this->etatForm;
    }

    /**
     * @param EtatForm $etatForm
     * @return EtatForm
     */
    public function setEtatForm(EtatForm $etatForm): EtatForm
    {
        $this->etatForm = $etatForm;
        return $this->etatForm;
    }

}