<?php

namespace Application\Form\HasPeriode;

trait HasPeriodeFormAwareTrait {

    private HasPeriodeForm $hasPeriodeForm;

    public function getHasDescriptionForm(): HasPeriodeForm
    {
        return $this->hasPeriodeForm;
    }

    public function setHasDescriptionForm(HasPeriodeForm $hasPeriodeForm): HasPeriodeForm
    {
        $this->hasPeriodeForm = $hasPeriodeForm;
        return $this->hasPeriodeForm;
    }

}