<?php

namespace Structure\Form\AjouterResponsable;

trait AjouterResponsableFormAwareTrait {

    /** @var AjouterResponsableForm */
    private $ajouterResponsableForm;

    /**
     * @return AjouterResponsableForm
     */
    public function getAjouterResponsableForm()
    {
        return $this->ajouterResponsableForm;
    }

    /**
     * @param AjouterResponsableForm $ajouterResponsableForm
     * @return AjouterResponsableForm
     */
    public function setAjouterResponsableForm(AjouterResponsableForm $ajouterResponsableForm)
    {
        $this->ajouterResponsableForm = $ajouterResponsableForm;
        return $this->ajouterResponsableForm;
    }

}