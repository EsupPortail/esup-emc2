<?php

namespace FicheMetier\Form\TendanceElement;

trait TendanceElementFormAwareTrait
{
    private TendanceElementForm $tendanceElementForm;

    public function getTendanceElementForm(): TendanceElementForm
    {
        return $this->tendanceElementForm;
    }

    public function setTendanceElementForm(TendanceElementForm $tendanceElementForm): void
    {
        $this->tendanceElementForm = $tendanceElementForm;
    }

}