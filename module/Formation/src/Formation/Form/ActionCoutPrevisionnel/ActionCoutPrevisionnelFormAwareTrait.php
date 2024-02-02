<?php

namespace Formation\Form\ActionCoutPrevisionnel;

trait ActionCoutPrevisionnelFormAwareTrait
{
    private ActionCoutPrevisionnelForm $actionCoutPrevisionnelForm;

    public function getActionCoutPrevisionnelForm(): ActionCoutPrevisionnelForm
    {
        return $this->actionCoutPrevisionnelForm;
    }

    public function setActionCoutPrevisionnelForm(ActionCoutPrevisionnelForm $actionCoutPrevisionnelForm): void
    {
        $this->actionCoutPrevisionnelForm = $actionCoutPrevisionnelForm;
    }

}