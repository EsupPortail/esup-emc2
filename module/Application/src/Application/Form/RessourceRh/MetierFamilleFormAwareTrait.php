<?php

namespace Application\Form\RessourceRh;

trait MetierFamilleFormAwareTrait {

    /** @var MetierFamilleForm $metierFamilleForm */
    private $metierFamilleForm;

    /**
     * @return MetierFamilleForm
     */
    public function getMetierFamilleForm()
    {
        return $this->metierFamilleForm;
    }

    /**
     * @param MetierFamilleForm $metierFamilleForm
     * @return MetierFamilleForm
     */
    public function setMetierFamilleForm($metierFamilleForm)
    {
        $this->metierFamilleForm = $metierFamilleForm;
        return $this->metierFamilleForm;
    }


}