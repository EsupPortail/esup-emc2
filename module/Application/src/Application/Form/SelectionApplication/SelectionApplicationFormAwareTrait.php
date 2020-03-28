<?php

namespace Application\Form\SelectionApplication;

trait SelectionApplicationFormAwareTrait {

    /** @var SelectionApplicationForm */
    private $selectionApplicationForm;

    /**
     * @return SelectionApplicationForm
     */
    public function getSelectionApplicationForm()
    {
        return $this->selectionApplicationForm;
    }

    /**
     * @param SelectionApplicationForm $selectionApplicationForm
     * @return SelectionApplicationForm
     */
    public function setSelectionApplicationForm($selectionApplicationForm)
    {
        $this->selectionApplicationForm = $selectionApplicationForm;
        return $this->selectionApplicationForm;
    }

}
