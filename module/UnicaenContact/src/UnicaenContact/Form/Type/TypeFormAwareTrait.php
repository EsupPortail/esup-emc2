<?php

namespace UnicaenContact\Form\Type;

trait TypeFormAwareTrait
{
    private TypeForm $typeForm;

    public function getTypeForm(): TypeForm
    {
        return $this->typeForm;
    }

    public function setTypeForm(TypeForm $typeForm): void
    {
        $this->typeForm = $typeForm;
    }

}