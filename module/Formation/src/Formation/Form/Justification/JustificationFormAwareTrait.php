<?php

namespace Formation\Form\Justification;

trait JustificationFormAwareTrait {

    private JustificationForm $justificationForm;

    public function getJustificationForm(): JustificationForm
    {
        return $this->justificationForm;
    }

    public function setJustificationForm(JustificationForm $justificationForm): void
    {
        $this->justificationForm = $justificationForm;
    }


}