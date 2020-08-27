<?php

namespace Application\Form\FonctionActivite;

trait FonctionActiviteFormAwareTrait {

    /** @var FonctionActiviteForm $fonctionActiviteForm */
    private $fonctionActiviteForm;

    /**
     * @return FonctionActiviteForm
     */
    public function getFonctionActivitenForm()
    {
        return $this->fonctionActiviteForm;
    }

    /**
     * @param FonctionActiviteForm $form
     * @return FonctionActiviteForm
     */
    public function setFonctionActiviteForm(FonctionActiviteForm  $form)
    {
        $this->fonctionActiviteForm = $form;
        return $this->fonctionActiviteForm;
    }
}