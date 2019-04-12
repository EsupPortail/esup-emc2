<?php

namespace Application\Form\FichePosteCreation;

trait FichePosteCreationFormAwareTrait {

    /** @var FichePosteCreationForm $ficherPosteCreationForm */
    private $ficherPosteCreationForm;

    /**
     * @return FichePosteCreationForm
     */
    public function getFichePosteCreationForm()
    {
        return $this->ficherPosteCreationForm;
    }

    /**
     * @param FichePosteCreationForm $ficherPosteCreationForm
     * @return FichePosteCreationForm
     */
    public function setFichePosteCreationForm($ficherPosteCreationForm)
    {
        $this->ficherPosteCreationForm = $ficherPosteCreationForm;
        return $this->ficherPosteCreationForm;
    }


}