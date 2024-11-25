<?php

namespace Application\Form\Chaine;

trait ChaineFormAwareTrait {

    protected ChaineForm $chaineForm;

    public function getChaineForm(): ChaineForm
    {
        return $this->chaineForm;
    }

    public function setChaineForm(ChaineForm $chaineForm): void
    {
        $this->chaineForm = $chaineForm;
    }

}