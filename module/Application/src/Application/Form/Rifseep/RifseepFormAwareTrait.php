<?php

namespace Application\Form\Rifseep;

trait RifseepFormAwareTrait {

    /** @var RifseepForm $rifseepForm */
    private $rifseepForm;

    /**
     * @return RifseepForm
     */
    public function getRifseepForm() : RifseepForm
    {
        return $this->rifseepForm;
    }

    /**
     * @param RifseepForm $rifseepForm
     * @return RifseepForm
     */
    public function setRifseepForm(RifseepForm $rifseepForm) : RifseepForm
    {
        $this->rifseepForm = $rifseepForm;
        return $this->rifseepForm;
    }


}