<?php

namespace Application\Service\RessourceRh;

use Application\Form\RessourceRh\MetierForm;

trait MetierFormAwareTrait {

    /** @var MetierForm $metierForm */
    private $metierForm;

    /**
     * @return MetierForm
     */
    public function getMetierForm()
    {
        return $this->metierForm;
    }

    /**
     * @param MetierForm $metierForm
     * @return MetierForm
     */
    public function setMetierForm($metierForm)
    {
        $this->metierForm = $metierForm;
        return $this->metierForm;
    }


}