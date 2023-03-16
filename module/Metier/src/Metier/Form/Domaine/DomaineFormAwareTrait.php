<?php

namespace Metier\Form\Domaine;

trait DomaineFormAwareTrait {

    private DomaineForm $domaineForm;

    public function getDomaineForm() : DomaineForm
    {
        return $this->domaineForm;
    }

    public function setDomaineForm(DomaineForm $domaineForm) : void
    {
        $this->domaineForm = $domaineForm;
    }

}