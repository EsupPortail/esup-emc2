<?php

namespace Application\Form\AjouterFicheMetier;

trait AjouterFicheMetierFormAwareTrait {

    /** @var AjouterFicheMetierForm $ajouterFicheMetierForm */
    private $ajouterFicheMetierForm;

    /**
     * @return AjouterFicheMetierForm
     */
    public function getAjouterFicheTypeForm()
    {
        return $this->ajouterFicheMetierForm;
    }

    /**
     * @param AjouterFicheMetierForm $ajouterFicheMetierForm
     * @return AjouterFicheMetierForm
     */
    public function setAjouterFicheTypeForm($ajouterFicheMetierForm)
    {
        $this->ajouterFicheMetierForm = $ajouterFicheMetierForm;
        return $this->ajouterFicheMetierForm;
    }
}