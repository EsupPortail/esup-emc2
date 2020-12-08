<?php

namespace UnicaenNote\Form\Type;


trait TypeFormAwareTrait {

    /** @var TypeForm */
    private $typeForm;

    /**
     * @return TypeForm
     */
    public function getTypeForm(): TypeForm
    {
        return $this->typeForm;
    }

    /**
     * @param TypeForm $typeForm
     * @return TypeForm
     */
    public function setTypeForm(TypeForm $typeForm): TypeForm
    {
        $this->typeForm = $typeForm;
        return $this->typeForm;
    }


}