<?php

namespace Application\Form\HasPeriode;

trait HasPeriodeFormAwareTrait {

    /** @var HasPeriodeForm */
    private $hasPeriodeForm;

    /**
     * @return HasPeriodeForm
     */
    public function getHasDescriptionForm(): HasPeriodeForm
    {
        return $this->hasPeriodeForm;
    }

    /**
     * @param HasPeriodeForm hasPeriodeForm
     * @return HasPeriodeForm
     */
    public function setHasDescriptionForm(HasPeriodeForm $hasPeriodeForm): HasPeriodeForm
    {
        $this->hasPeriodeForm = $hasPeriodeForm;
        return $this->hasPeriodeForm;
    }

}