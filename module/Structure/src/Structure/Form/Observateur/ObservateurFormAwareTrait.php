<?php

namespace Structure\Form\Observateur;

trait ObservateurFormAwareTrait
{

    private ObservateurForm $observateurForm;

    public function getObservateurForm(): ObservateurForm
    {
        return $this->observateurForm;
    }

    public function setObservateurForm(ObservateurForm $observateurForm): void
    {
        $this->observateurForm = $observateurForm;
    }

}