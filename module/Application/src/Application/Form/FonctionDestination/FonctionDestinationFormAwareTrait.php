<?php

namespace Application\Form\FonctionDestination;

trait FonctionDestinationFormAwareTrait {

    /** @var FonctionDestinationForm $fonctionDestinationForm */
    private $fonctionDestinationForm;

    /**
     * @return FonctionDestinationForm
     */
    public function getFonctionDestinationForm()
    {
        return $this->fonctionDestinationForm;
    }

    /**
     * @param FonctionDestinationForm $form
     * @return FonctionDestinationForm
     */
    public function setFonctionDestinationForm(FonctionDestinationForm  $form)
    {
        $this->fonctionDestinationForm = $form;
        return $this->fonctionDestinationForm;
    }
}