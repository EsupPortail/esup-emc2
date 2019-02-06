<?php

namespace Application\Form\Affectation;

trait AffectationFormAwareTrait {

    /** @var AffectationForm $affectationForm */
    private $affectationForm;

    /**
     * @return AffectationForm
     */
    public function getAffectationForm()
    {
        return $this->affectationForm;
    }

    /**
     * @param AffectationForm $affectationForm
     * @return AffectationForm
     */
    public function setAffectationForm($affectationForm)
    {
        $this->affectationForm = $affectationForm;
        return $this->affectationForm;
    }


}