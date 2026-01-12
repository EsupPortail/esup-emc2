<?php

namespace FicheMetier\Form\Activite;

trait ActiviteFormAwareTrait
{
    private ActiviteForm $activiteForm;

    public function getActiviteForm(): ActiviteForm
    {
        return $this->activiteForm;
    }

    public function setActiviteForm(ActiviteForm $activiteForm): void
    {
        $this->activiteForm = $activiteForm;
    }

}
