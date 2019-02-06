<?php

namespace Application\Form\RessourceRh;

trait CorrespondanceFormAwareTrait {

    /** @var CorrespondanceForm $correspondanceForm */
    private $correspondanceForm;

    /**
     * @return CorrespondanceForm
     */
    public function getCorrespondanceForm()
    {
        return $this->correspondanceForm;
    }

    /**
     * @param CorrespondanceForm $correspondanceForm
     * @return CorrespondanceForm
     */
    public function setCorrespondanceForm($correspondanceForm)
    {
        $this->correspondanceForm = $correspondanceForm;
        return $this->correspondanceForm;
    }


}