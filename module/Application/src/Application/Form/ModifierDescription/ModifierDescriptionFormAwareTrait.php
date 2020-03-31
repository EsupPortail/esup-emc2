<?php

namespace Application\Form\ModifierDescription;

trait ModifierDescriptionFormAwareTrait {

    /** @var ModifierDescriptionForm */
    private $modifierDescriptionForm;

    /**
     * @return ModifierDescriptionForm
     */
    public function getModifierDescriptionForm()
    {
        return $this->modifierDescriptionForm;
    }

    /**
     * @param ModifierDescriptionForm $modifierDescriptionForm
     * @return ModifierDescriptionForm
     */
    public function setModifierDescriptionForm($modifierDescriptionForm)
    {
        $this->modifierDescriptionForm = $modifierDescriptionForm;
        return $this->modifierDescriptionForm;
    }

}