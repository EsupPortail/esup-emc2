<?php

namespace Application\Form\ParcoursDeFormation;

trait ParcoursDeFormationFormAwareTrait {

    /** @var ParcoursDeFormationForm */
    private $parcoursDeFormationForm;

    /**
     * @return ParcoursDeFormationForm
     */
    public function getParcoursDeFormationForm()
    {
        return $this->parcoursDeFormationForm;
    }

    /**
     * @param ParcoursDeFormationForm $parcoursDeFormationForm
     * @return ParcoursDeFormationForm
     */
    public function setParcoursDeFormationForm($parcoursDeFormationForm)
    {
        $this->parcoursDeFormationForm = $parcoursDeFormationForm;
        return $this->parcoursDeFormationForm;
    }

}