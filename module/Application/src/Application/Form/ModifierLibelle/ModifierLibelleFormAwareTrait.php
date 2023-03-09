<?php

namespace Application\Form\ModifierLibelle;

trait ModifierLibelleFormAwareTrait {

    private ModifierLibelleForm $modifierLibelleForm;

    public function getModifierLibelleForm() : ModifierLibelleForm
    {
        return $this->modifierLibelleForm;
    }

    public function setModifierLibelleForm(ModifierLibelleForm $modifierLibelleForm) : void
    {
        $this->modifierLibelleForm = $modifierLibelleForm;
    }

}