<?php

namespace Formation\Form\Justificatif;

trait JustificatifFormAwareTrait {

    private JustificatifForm $justificatifForm;

    public function getJustificatifForm(): JustificatifForm
    {
        return $this->justificatifForm;
    }

    public function setJustificatifForm(JustificatifForm $justificatifForm): void
    {
        $this->justificatifForm = $justificatifForm;
    }

}