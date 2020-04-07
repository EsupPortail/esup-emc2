<?php

namespace Application\Form\AjouterGestionnaire;

trait AjouterGestionnaireFormAwareTrait {

    /** @var AjouterGestionnaireForm */
    private $ajouterGestionnaireForm;

    /**
     * @return AjouterGestionnaireForm
     */
    public function getAjouterGestionnaireForm()
    {
        return $this->ajouterGestionnaireForm;
    }

    /**
     * @param AjouterGestionnaireForm $ajouterGestionnaireForm
     * @return AjouterGestionnaireForm
     */
    public function setAjouterGestionnaireForm(AjouterGestionnaireForm $ajouterGestionnaireForm)
    {
        $this->ajouterGestionnaireForm = $ajouterGestionnaireForm;
        return $this->ajouterGestionnaireForm;
    }

}