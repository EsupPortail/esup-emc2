<?php

namespace Application\Form\ModifierRattachement;

trait ModifierRattachementFormAwareTrait {

    /** @var ModifierRattachementForm $modifierRattachementForm */
    private $modifierRattachementForm;

    /**
     * @return ModifierRattachementForm
     */
    public function getModifierRattachementForm()
    {
        return $this->modifierRattachementForm;
    }

    /**
     * @param ModifierRattachementForm $form
     * @return ModifierRattachementForm
     */
    public function setModifierRattachementForm(ModifierRattachementForm $form)
    {
        $this->modifierRattachementForm = $form;
        return $this->modifierRattachementForm;
    }
}