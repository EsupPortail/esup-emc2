<?php

namespace Application\Form\AssocierPoste;

trait AssocierPosteFormAwareTrait {

    /**  @var AssocierPosteForm $associerPosteForm */
    private $associerPosteForm;

    /**
     * @return AssocierPosteForm
     */
    public function getAssocierPosteForm()
    {
        return $this->associerPosteForm;
    }

    /**
     * @param AssocierPosteForm $associerPosteForm
     * @return AssocierPosteForm
     */
    public function setAssocierPosteForm($associerPosteForm)
    {
        $this->associerPosteForm = $associerPosteForm;
        return $this->associerPosteForm;
    }


}