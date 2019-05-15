<?php

namespace Application\Form\FicheMetier;

trait ActiviteExistanteFormAwareTrait {

    /** @var ActiviteExistanteForm $activiteExistanteForm */
    private $activiteExistanteForm;

    /**
     * @return ActiviteExistanteForm
     */
    public function getActiviteExistanteForm()
    {
        return $this->activiteExistanteForm;
    }

    /**
     * @param ActiviteExistanteForm $activiteExistanteForm
     * @return ActiviteExistanteForm
     */
    public function setActiviteExistanteForm($activiteExistanteForm)
    {
        $this->activiteExistanteForm = $activiteExistanteForm;
        return $this->activiteExistanteForm;
    }


}