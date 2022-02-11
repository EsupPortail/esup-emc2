<?php

namespace Application\Form\Complement;

trait ComplementFormAwareTrait {

    /**
     * @var ComplementForm
     */
    private $complementForm;

    /**
     * @return ComplementForm
     */
    public function getComplementForm(): ComplementForm
    {
        return $this->complementForm;
    }

    /**
     * @param ComplementForm $complementForm
     * @return ComplementForm
     */
    public function setComplementForm(ComplementForm $complementForm): ComplementForm
    {
        $this->complementForm = $complementForm;
        return $this->complementForm;
    }

}