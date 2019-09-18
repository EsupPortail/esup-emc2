<?php

namespace Application\Form\AssocierTitre;

trait AssocierTitreFormAwareTrait {

    /** @var AssocierTitreForm $associerTitreForm */
    private $associerTitreForm;

    /**
     * @return AssocierTitreForm
     */
    public function getAssocierTitreForm()
    {
        return $this->associerTitreForm;
    }

    /**
     * @param AssocierTitreForm $associerTitreForm
     * @return AssocierTitreForm
     */
    public function setAssocierTitreForm($associerTitreForm)
    {
        $this->associerTitreForm = $associerTitreForm;
        return $this->associerTitreForm;
    }


}