<?php

namespace EmploiRepere\Form\EmploiRepere;

trait EmploiRepereFormAwareTrait {

    private EmploiRepereForm $emploiRepereForm;

    public function getEmploiRepereForm(): EmploiRepereForm
    {
        return $this->emploiRepereForm;
    }

    public function setEmploiRepereForm(EmploiRepereForm $emploiRepereForm): void
    {
        $this->emploiRepereForm = $emploiRepereForm;
    }

}
