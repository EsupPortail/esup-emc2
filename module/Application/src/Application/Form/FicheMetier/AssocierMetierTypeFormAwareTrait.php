<?php

namespace Application\Form\FicheMetier;

trait AssocierMetierTypeFormAwareTrait {

    /** @var AssocierMetierTypeForm $associerMetierTypeForm */
    private $associerMetierTypeForm;

    /**
     * @return AssocierMetierTypeForm
     */
    public function getAssocierMetierTypeForm()
    {
        return $this->associerMetierTypeForm;
    }

    /**
     * @param AssocierMetierTypeForm $associerMetierTypeForm
     * @return AssocierMetierTypeForm
     */
    public function setAssocierMetierTypeForm($associerMetierTypeForm)
    {
        $this->associerMetierTypeForm = $associerMetierTypeForm;
        return $this->associerMetierTypeForm;
    }


}