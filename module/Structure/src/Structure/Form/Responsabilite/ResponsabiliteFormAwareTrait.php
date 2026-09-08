<?php

namespace Structure\Form\Responsabilite;

trait ResponsabiliteFormAwareTrait {

    protected ResponsabiliteForm $responsabiliteForm;

    public function getResponsabiliteForm(): ResponsabiliteForm
    {
        return $this->responsabiliteForm;
    }

    public function setResponsabiliteForm(ResponsabiliteForm $responsabiliteForm): void
    {
        $this->responsabiliteForm = $responsabiliteForm;
    }

}