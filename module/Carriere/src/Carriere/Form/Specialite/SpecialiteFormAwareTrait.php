<?php

namespace Carriere\Form\Specialite;

trait SpecialiteFormAwareTrait {

    private SpecialiteForm $specialiteForm;

    public function getSpecialiteForm(): SpecialiteForm
    {
        return $this->specialiteForm;
    }

    public function setSpecialiteForm(SpecialiteForm $specialiteForm): void
    {
        $this->specialiteForm = $specialiteForm;
    }


}
