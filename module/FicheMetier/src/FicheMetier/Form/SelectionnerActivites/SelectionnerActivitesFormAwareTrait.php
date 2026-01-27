<?php

namespace FicheMetier\Form\SelectionnerActivites;

trait SelectionnerActivitesFormAwareTrait
{
    private SelectionnerActivitesForm $selectionnerActivitesForm;

    public function getSelectionnerActivitesForm(): SelectionnerActivitesForm
    {
        return $this->selectionnerActivitesForm;
    }

    public function setSelectionnerActivitesForm(SelectionnerActivitesForm $selectionnerActivitesForm): void
    {
        $this->selectionnerActivitesForm = $selectionnerActivitesForm;
    }
}
