<?php

namespace Application\Form\Poste;

trait PosteFormAwareTrait {

    /** @var PosteForm $posteForm */
    private $posteForm;

    /**
     * @return PosteForm
     */
    public function getPosteForm()
    {
        return $this->posteForm;
    }

    /**
     * @param PosteForm $posteForm
     * @return PosteForm
     */
    public function setPosteForm($posteForm)
    {
        $this->posteForm = $posteForm;
        return $this->posteForm;
    }


}