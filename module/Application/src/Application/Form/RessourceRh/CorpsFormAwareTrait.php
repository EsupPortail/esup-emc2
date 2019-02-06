<?php

namespace Application\Form\RessourceRh;

trait CorpsFormAwareTrait {

    /** @var CorpsForm $corpsForm */
    private $corpsForm;

    /**
     * @return CorpsForm
     */
    public function getCorpsForm()
    {
        return $this->corpsForm;
    }

    /**
     * @param CorpsForm $corpsForm
     * @return CorpsForm
     */
    public function setCorpsForm($corpsForm)
    {
        $this->corpsForm = $corpsForm;
        return $this->corpsForm;
    }


}