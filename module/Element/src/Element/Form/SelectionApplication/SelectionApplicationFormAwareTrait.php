<?php

namespace Element\Form\SelectionApplication;

trait SelectionApplicationFormAwareTrait {

    /** @var SelectionApplicationForm */
    private $selectionApplicationForm;

    /**
     * @return SelectionApplicationForm
     */
    public function getSelectionApplicationForm() : SelectionApplicationForm
    {
        return $this->selectionApplicationForm;
    }

    /**
     * @param SelectionApplicationForm $selectionApplicationForm
     * @return SelectionApplicationForm
     */
    public function setSelectionApplicationForm(SelectionApplicationForm $selectionApplicationForm) : SelectionApplicationForm
    {
        $this->selectionApplicationForm = $selectionApplicationForm;
        return $this->selectionApplicationForm;
    }

}
