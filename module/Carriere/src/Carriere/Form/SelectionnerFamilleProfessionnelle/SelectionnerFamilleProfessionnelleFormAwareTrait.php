<?php

namespace Carriere\Form\SelectionnerFamilleProfessionnelle;

trait SelectionnerFamilleProfessionnelleFormAwareTrait
{

    private SelectionnerFamilleProfessionnelleForm $selectionnerFamilleProfessionnelleForm;

    public function getSelectionnerFamilleProfessionnelleForm(): SelectionnerFamilleProfessionnelleForm
    {
        return $this->selectionnerFamilleProfessionnelleForm;
    }

    public function setSelectionnerFamilleProfessionnelleForm(SelectionnerFamilleProfessionnelleForm $selectionnerFamilleProfessionnelleForm): void
    {
        $this->selectionnerFamilleProfessionnelleForm = $selectionnerFamilleProfessionnelleForm;
    }

}