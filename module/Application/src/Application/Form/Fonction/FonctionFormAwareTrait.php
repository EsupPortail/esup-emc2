<?php

namespace Application\Form\Fonction;

trait FonctionFormAwareTrait {

    /** @var FonctionForm $fonctionForm */
    private $fonctionForm;

    /**
     * @return FonctionForm
     */
    public function getFonctionForm()
    {
        return $this->fonctionForm;
    }

    /**
     * @param FonctionForm $fonctionForm
     * @return FonctionForm
     */
    public function setFonctionForm($fonctionForm)
    {
        $this->fonctionForm = $fonctionForm;
        return $this->fonctionForm;
    }


}