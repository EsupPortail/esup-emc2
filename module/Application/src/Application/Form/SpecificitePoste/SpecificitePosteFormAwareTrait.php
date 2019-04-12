<?php

namespace Application\Form\SpecificitePoste;

trait SpecificitePosteFormAwareTrait {

    /** @var SpecificitePosteForm $specificitePosteForm */
    private $specificitePosteForm;

    /**
     * @return SpecificitePosteForm
     */
    public function getSpecificitePosteForm()
    {
        return $this->specificitePosteForm;
    }

    /**
     * @param SpecificitePosteForm $specificitePosteForm
     * @return SpecificitePosteForm
     */
    public function setSpecificitePosteForm($specificitePosteForm)
    {
        $this->specificitePosteForm = $specificitePosteForm;
        return $this->specificitePosteForm;
    }


}