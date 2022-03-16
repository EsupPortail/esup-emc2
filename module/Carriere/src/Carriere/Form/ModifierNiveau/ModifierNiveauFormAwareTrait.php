<?php

namespace Carriere\Form\ModifierNiveau;

trait ModifierNiveauFormAwareTrait {

    /** @var ModifierNiveauForm */
    private $modifierNiveauForm;

    /**
     * @return ModifierNiveauForm
     */
    public function getModifierNiveauForm()
    {
        return $this->modifierNiveauForm;
    }

    /**
     * @param ModifierNiveauForm $modifierNiveauForm
     * @return ModifierNiveauFormAwareTrait
     */
    public function setModifierNiveauForm($modifierNiveauForm)
    {
        $this->modifierNiveauForm = $modifierNiveauForm;
        return $this;
    }

}