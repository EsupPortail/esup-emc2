<?php

namespace UnicaenDocument\Form\Macro;

trait MacroFormAwareTrait {

    /** @var MacroForm */
    private $macroForm;

    /**
     * @return MacroForm
     */
    public function getMacroForm()
    {
        return $this->macroForm;
    }

    /**
     * @param MacroForm $macroForm
     * @return MacroForm
     */
    public function setMacroForm(MacroForm $macroForm)
    {
        $this->macroForm = $macroForm;
        return $this->macroForm;
    }


}