<?php

namespace Metier\Form\Reference;

trait ReferenceFormAwareTrait {

    /** @var ReferenceForm */
    private $referenceForm;

    /**
     * @return ReferenceForm
     */
    public function getReferenceForm()
    {
        return $this->referenceForm;
    }

    /**
     * @param ReferenceForm $referenceForm
     * @return ReferenceForm
     */
    public function setReferenceForm(ReferenceForm $referenceForm)
    {
        $this->referenceForm = $referenceForm;
        return $this->referenceForm;
    }
}