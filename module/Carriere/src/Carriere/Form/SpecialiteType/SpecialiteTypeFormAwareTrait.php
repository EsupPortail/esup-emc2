<?php

namespace Carriere\Form\SpecialiteType;

trait SpecialiteTypeFormAwareTrait {

    private SpecialiteTypeForm $specialiteTypeForm;

    public function getSpecialiteTypeForm(): SpecialiteTypeForm
    {
        return $this->specialiteTypeForm;
    }

    public function setSpecialiteTypeForm(SpecialiteTypeForm $specialiteTypeForm): void
    {
        $this->specialiteTypeForm = $specialiteTypeForm;
    }


}
