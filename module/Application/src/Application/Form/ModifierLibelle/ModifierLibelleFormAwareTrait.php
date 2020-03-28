<?php

namespace Application\Form\ModifierLibelle;

trait ModifierLibelleFormAwareTrait {

    /** @var ModifierLibelleForm */
    private $modifierLibelleForm;

    /**
     * @return ModifierLibelleForm
     */
    public function getModifierLibelleForm()
    {
        return $this->modifierLibelleForm;
    }

    /**
     * @param ModifierLibelleForm $modifierLibelleForm
     * @return ModifierLibelleForm
     */
    public function setModifierLibelleForm($modifierLibelleForm)
    {
        $this->modifierLibelleForm = $modifierLibelleForm;
        return $this->modifierLibelleForm;
    }

}