<?php

namespace Element\Form\SelectionNiveau;

trait SelectionNiveauFormAwareTrait {

    private SelectionNiveauForm $selectionNiveauForm;

    public function getSelectionNiveauForm(): SelectionNiveauForm
    {
        return $this->selectionNiveauForm;
    }

    public function setSelectionNiveauForm(SelectionNiveauForm $selectionNiveauForm): void
    {
        $this->selectionNiveauForm = $selectionNiveauForm;
    }

}