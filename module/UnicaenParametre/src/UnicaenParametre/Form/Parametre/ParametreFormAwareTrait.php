<?php

namespace UnicaenParametre\Form\Parametre;

trait ParametreFormAwareTrait {

    /** @var ParametreForm */
    private $parametreForm;

    /**
     * @return ParametreForm
     */
    public function getParametreForm(): ParametreForm
    {
        return $this->parametreForm;
    }

    /**
     * @param ParametreForm $parametreForm
     * @return ParametreForm
     */
    public function setParametreForm(ParametreForm $parametreForm): ParametreForm
    {
        $this->parametreForm = $parametreForm;
        return $this->parametreForm;
    }
}