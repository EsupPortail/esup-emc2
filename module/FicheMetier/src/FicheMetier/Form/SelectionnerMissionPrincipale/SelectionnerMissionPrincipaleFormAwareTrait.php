<?php

namespace FicheMetier\Form\SelectionnerMissionPrincipale;

trait SelectionnerMissionPrincipaleFormAwareTrait
{
    private SelectionnerMissionPrincipaleForm $selectionnerMissionPrincipaleForm;

    public function getSelectionnerMissionPrincipaleForm(): SelectionnerMissionPrincipaleForm
    {
        return $this->selectionnerMissionPrincipaleForm;
    }

    public function setSelectionnerMissionPrincipaleForm(SelectionnerMissionPrincipaleForm $selectionnerMissionPrincipaleForm): void
    {
        $this->selectionnerMissionPrincipaleForm = $selectionnerMissionPrincipaleForm;
    }

}
