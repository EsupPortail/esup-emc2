<?php

namespace Application\Form\EntretienProfesionnelFormulaire;

trait EntretienProfessionnelFormulaireFormAwareTrait {

    /** @var EntretienProfessionnelFormulaireForm */
    private $entretienProfessionnelFormulaireForm;

    /**
     * @return EntretienProfessionnelFormulaireForm
     */
    public function getEntretienProfessionnelFormulaireForm()
    {
        return $this->entretienProfessionnelFormulaireForm;
    }

    /**
     * @param EntretienProfessionnelFormulaireForm $entretienProfessionnelFormulaireForm
     * @return EntretienProfessionnelFormulaireForm
     */
    public function setEntretienProfessionnelFormulaireForm($entretienProfessionnelFormulaireForm)
    {
        $this->entretienProfessionnelFormulaireForm = $entretienProfessionnelFormulaireForm;
        return $this->entretienProfessionnelFormulaireForm;
    }
}