<?php

namespace Metier\Form\Metier;

trait MetierFormAwareTrait {

    private MetierForm $metierForm;

    public function getMetierForm() : MetierForm
    {
        return $this->metierForm;
    }

    public function setMetierForm(MetierForm $metierForm) : void
    {
        $this->metierForm = $metierForm;
    }

}