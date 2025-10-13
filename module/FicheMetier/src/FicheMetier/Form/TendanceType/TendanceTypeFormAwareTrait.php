<?php

namespace FicheMetier\Form\TendanceType;

trait TendanceTypeFormAwareTrait
{
    private TendanceTypeForm $tendanceTypeForm;

    public function getTendanceTypeForm(): TendanceTypeForm
    {
        return $this->tendanceTypeForm;
    }

    public function setTendanceTypeForm(TendanceTypeForm $tendanceTypeForm): void
    {
        $this->tendanceTypeForm = $tendanceTypeForm;
    }

}