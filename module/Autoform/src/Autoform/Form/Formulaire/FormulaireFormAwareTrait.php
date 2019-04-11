<?php

namespace Autoform\Form\Formulaire;

trait FormulaireFormAwareTrait {

    /** @var FormulaireForm $formulaireForm */
    private $formulaireForm;

    /**
     * @return FormulaireForm
     */
    public function getFormulaireForm()
    {
        return $this->formulaireForm;
    }

    /**
     * @param FormulaireForm $formulaireForm
     * @return FormulaireForm
     */
    public function setFormulaireForm($formulaireForm)
    {
        $this->formulaireForm = $formulaireForm;
        return $this->formulaireForm;
    }


}