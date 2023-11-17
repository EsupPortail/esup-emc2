<?php

namespace Formation\Form\Axe;

trait AxeFormAwareTrait
{

    private AxeForm $axeForm;

    public function getAxeForm(): AxeForm
    {
        return $this->axeForm;
    }

    public function setAxeForm(AxeForm $axeForm): void
    {
        $this->axeForm = $axeForm;
    }


}