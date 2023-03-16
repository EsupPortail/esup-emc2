<?php

namespace Metier\Form\Reference;

trait ReferenceFormAwareTrait {

    private ReferenceForm $referenceForm;

    public function getReferenceForm() : ReferenceForm
    {
        return $this->referenceForm;
    }

    public function setReferenceForm(ReferenceForm $referenceForm) : void
    {
        $this->referenceForm = $referenceForm;
    }
}