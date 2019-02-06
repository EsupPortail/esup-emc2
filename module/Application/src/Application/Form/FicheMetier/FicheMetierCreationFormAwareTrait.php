<?php

namespace Application\Form\FicheMetier;

trait FicheMetierCreationFormAwareTrait {

    /** @var FicheMetierCreationForm $ficherMetierCreationForm */
    private $ficherMetierCreationForm;

    /**
     * @return FicheMetierCreationForm
     */
    public function getFicherMetierCreationForm()
    {
        return $this->ficherMetierCreationForm;
    }

    /**
     * @param FicheMetierCreationForm $ficherMetierCreationForm
     * @return FicheMetierCreationForm
     */
    public function setFicherMetierCreationForm($ficherMetierCreationForm)
    {
        $this->ficherMetierCreationForm = $ficherMetierCreationForm;
        return $this->ficherMetierCreationForm;
    }


}