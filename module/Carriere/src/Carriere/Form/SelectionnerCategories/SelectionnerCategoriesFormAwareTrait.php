<?php

namespace Carriere\Form\SelectionnerCategories;

trait SelectionnerCategoriesFormAwareTrait {

    private SelectionnerCategoriesForm $selectionnerCategoriesForm;

    public function getSelectionnerCategoriesForm(): SelectionnerCategoriesForm
    {
        return $this->selectionnerCategoriesForm;
    }

    public function setSelectionnerCategoriesForm(SelectionnerCategoriesForm $selectionnerCategoriesForm): void
    {
        $this->selectionnerCategoriesForm = $selectionnerCategoriesForm;
    }
}
