<?php

namespace Element\Form\SelectionApplication;

trait SelectionApplicationFormAwareTrait {

    private SelectionApplicationForm $selectionApplicationForm;

    public function getSelectionApplicationForm() : SelectionApplicationForm
    {
        return $this->selectionApplicationForm;
    }

    public function setSelectionApplicationForm(SelectionApplicationForm $selectionApplicationForm) : void
    {
        $this->selectionApplicationForm = $selectionApplicationForm;
    }

}
