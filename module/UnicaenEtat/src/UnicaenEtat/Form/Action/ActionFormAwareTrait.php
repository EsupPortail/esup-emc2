<?php

namespace UnicaenEtat\Form\Action;

trait ActionFormAwareTrait {

    /** @var ActionForm */
    private $etatForm;

    /**
     * @return ActionForm
     */
    public function getActionForm(): ActionForm
    {
        return $this->etatForm;
    }

    /**
     * @param ActionForm $etatForm
     * @return ActionForm
     */
    public function setActionForm(ActionForm $etatForm): ActionForm
    {
        $this->etatForm = $etatForm;
        return $this->etatForm;
    }
}