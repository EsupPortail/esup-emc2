<?php

namespace Application\Form\FicheMetier;

trait AjouterFicheTypeFormAwareTrait {

    /** @var AjouterFicheTypeForm $ajouterFicheTypeForm */
    private $ajouterFicheTypeForm;

    /**
     * @return AjouterFicheTypeForm
     */
    public function getAjouterFicheTypeForm()
    {
        return $this->ajouterFicheTypeForm;
    }

    /**
     * @param AjouterFicheTypeForm $ajouterFicheTypeForm
     * @return AjouterFicheTypeForm
     */
    public function setAjouterFicheTypeForm($ajouterFicheTypeForm)
    {
        $this->ajouterFicheTypeForm = $ajouterFicheTypeForm;
        return $this->ajouterFicheTypeForm;
    }
}