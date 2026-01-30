<?php

namespace Carriere\Form\SelectionnerFamillesProfessionnelles;

trait SelectionnerFamillesProfessionnellesFormAwareTrait
{

    private SelectionnerFamillesProfessionnellesForm $selectionnerFamillesProfessionnellesForm;

    public function getSelectionnerFamillesProfessionnellesForm(): SelectionnerFamillesProfessionnellesForm
    {
        return $this->selectionnerFamillesProfessionnellesForm;
    }

    public function setSelectionnerFamillesProfessionnellesForm(SelectionnerFamillesProfessionnellesForm $selectionnerFamillesProfessionnellesForm): void
    {
        $this->selectionnerFamillesProfessionnellesForm = $selectionnerFamillesProfessionnellesForm;
    }

}