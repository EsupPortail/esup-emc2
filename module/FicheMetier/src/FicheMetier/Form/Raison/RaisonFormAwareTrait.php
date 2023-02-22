<?php

namespace FicheMetier\Form\Raison;

trait RaisonFormAwareTrait {

    private RaisonForm $raisonForm;

    public function getRaisonForm(): RaisonForm
    {
        return $this->raisonForm;
    }

    public function setRaisonForm(RaisonForm $raisonForm): void
    {
        $this->raisonForm = $raisonForm;
    }

}