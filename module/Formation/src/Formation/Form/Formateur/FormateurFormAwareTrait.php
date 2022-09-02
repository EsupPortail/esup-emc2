<?php

namespace Formation\Form\Formateur;

trait FormateurFormAwareTrait
{
    private FormateurForm $formateurForm;

    /**
     * @return FormateurForm
     */
    public function getFormateurForm(): FormateurForm
    {
        return $this->formateurForm;
    }

    /**
     * @param FormateurForm $formateurForm
     * @return FormateurForm
     */
    public function setFormateurForm(FormateurForm $formateurForm): FormateurForm
    {
        $this->formateurForm = $formateurForm;
        return $this->formateurForm;
    }
}