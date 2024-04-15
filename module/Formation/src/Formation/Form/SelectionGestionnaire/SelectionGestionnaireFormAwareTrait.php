<?php

namespace Formation\Form\SelectionGestionnaire;

trait SelectionGestionnaireFormAwareTrait
{

    private SelectionGestionnaireForm $selectionGestionnaireForm;

    public function getSelectionGestionnaireForm(): SelectionGestionnaireForm
    {
        return $this->selectionGestionnaireForm;
    }

    public function setSelectionGestionnaireForm(SelectionGestionnaireForm $selectionGestionnaireForm): void
    {
        $this->selectionGestionnaireForm = $selectionGestionnaireForm;
    }

}
