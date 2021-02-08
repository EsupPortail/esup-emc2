<?php

namespace Metier\Form\Metier;

trait MetierFormAwareTrait {

    /** @var MetierForm $metierForm */
    private $metierForm;

    /**
     * @return MetierForm
     */
    public function getMetierForm() : MetierForm
    {
        return $this->metierForm;
    }

    /**
     * @param MetierForm $metierForm
     * @return MetierForm
     */
    public function setMetierForm(MetierForm $metierForm)  :MetierForm
    {
        $this->metierForm = $metierForm;
        return $this->metierForm;
    }


}