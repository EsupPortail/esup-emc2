<?php

namespace Carriere\Form\SelectionnerCategorie;

trait SelectionnerCategorieFormAwareTrait {

    private SelectionnerCategorieForm $selectionnerCategorieForm;

    public function getSelectionnerCategorieForm(): SelectionnerCategorieForm
    {
        return $this->selectionnerCategorieForm;
    }

    public function setSelectionnerCategorieForm(SelectionnerCategorieForm $selectionnerCategorieForm): void
    {
        $this->selectionnerCategorieForm = $selectionnerCategorieForm;
    }
}
