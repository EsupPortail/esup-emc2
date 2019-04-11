<?php

namespace Application\Form\FicheMetierType;

trait LibelleFormAwareTrait {

    /** @var LibelleForm $libelleForm */
    private $libelleForm;

    /**
     * @return LibelleForm
     */
    public function getLibelleForm()
    {
        return $this->libelleForm;
    }

    /**
     * @param LibelleForm $libelleForm
     * @return LibelleForm
     */
    public function setLibelleForm($libelleForm)
    {
        $this->libelleForm = $libelleForm;
        return $this->libelleForm;
    }


}