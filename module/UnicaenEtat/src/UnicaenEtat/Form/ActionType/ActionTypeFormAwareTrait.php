<?php

namespace UnicaenEtat\Form\ActionType;

trait ActionTypeFormAwareTrait {

    /** @var ActionTypeForm */
    private $etatTypeForm;

    /**
     * @return ActionTypeForm
     */
    public function getActionTypeForm(): ActionTypeForm
    {
        return $this->etatTypeForm;
    }

    /**
     * @param ActionTypeForm $etatTypeForm
     * @return ActionTypeForm
     */
    public function setActionTypeForm(ActionTypeForm $etatTypeForm): ActionTypeForm
    {
        $this->etatTypeForm = $etatTypeForm;
        return $this->etatTypeForm;
    }
}