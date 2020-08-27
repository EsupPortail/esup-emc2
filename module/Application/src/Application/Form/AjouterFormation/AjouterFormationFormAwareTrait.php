<?php

namespace Application\Form\AjouterFormation;

trait AjouterFormationFormAwareTrait {

    /** @var AjouterFormationForm $ajouterFormationForm */
    private $ajouterFormationForm;

    /**
     * @return AjouterFormationForm
     */
    public function getAjouterFormationForm()
    {
        return $this->ajouterFormationForm;
    }

    /**
     * @param AjouterFormationForm $form
     * @return AjouterFormationForm
     */
    public function setAjouterFormationForm(AjouterFormationForm  $form)
    {
        $this->ajouterFormationForm = $form;
        return $this->ajouterFormationForm;
    }

}