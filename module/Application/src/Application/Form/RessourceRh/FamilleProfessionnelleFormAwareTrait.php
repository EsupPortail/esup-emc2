<?php

namespace Application\Form\RessourceRh;

trait FamilleProfessionnelleFormAwareTrait {

    /** @var FamilleProfessionnelleForm $familleProfessionnelleForm */
    private $familleProfessionnelleForm;

    /**
     * @return FamilleProfessionnelleForm
     */
    public function getFamilleProfessionnelleForm()
    {
        return $this->familleProfessionnelleForm;
    }

    /**
     * @param FamilleProfessionnelleForm $familleProfessionnelleForm
     * @return FamilleProfessionnelleForm
     */
    public function setFamilleProfessionnelleForm($familleProfessionnelleForm)
    {
        $this->familleProfessionnelleForm = $familleProfessionnelleForm;
        return $this->familleProfessionnelleForm;
    }


}