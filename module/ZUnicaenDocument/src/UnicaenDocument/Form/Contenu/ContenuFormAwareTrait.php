<?php

namespace UnicaenDocument\Form\Contenu;

trait ContenuFormAwareTrait {

    /** @var ContenuForm */
    private $contenuForm;

    /**
     * @return ContenuForm
     */
    public function getContenuForm(): ContenuForm
    {
        return $this->contenuForm;
    }

    /**
     * @param ContenuForm $contenuForm
     * @return ContenuForm
     */
    public function setContenuForm(ContenuForm $contenuForm)
    {
        $this->contenuForm = $contenuForm;
        return $this->contenuForm;
    }

}