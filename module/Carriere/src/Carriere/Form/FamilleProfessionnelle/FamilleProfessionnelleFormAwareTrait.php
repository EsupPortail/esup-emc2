<?php

namespace Carriere\Form\FamilleProfessionnelle;

trait FamilleProfessionnelleFormAwareTrait {

    private FamilleProfessionnelleForm $familleProfessionnelleForm;

    public function getFamilleProfessionnelleForm(): FamilleProfessionnelleForm
    {
        return $this->familleProfessionnelleForm;
    }

    public function setFamilleProfessionnelleForm(FamilleProfessionnelleForm $familleProfessionnelleForm): void
    {
        $this->familleProfessionnelleForm = $familleProfessionnelleForm;
    }

}
