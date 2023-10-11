<?php

namespace Formation\Form\Formateur;

trait FormateurFormAwareTrait
{
    private FormateurForm $formateurForm;

    public function getFormateurForm(): FormateurForm
    {
        return $this->formateurForm;
    }

    public function setFormateurForm(FormateurForm $formateurForm): void
    {
        $this->formateurForm = $formateurForm;
    }
}