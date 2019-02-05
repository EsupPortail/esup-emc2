<?php

namespace Application\Form\Activite;

trait ActiviteFormAwareTrait {

    /** @var ActiviteForm */
    private $activiteForm;

    /**
     * @return ActiviteForm
     */
    public function getActiviteForm()
    {
        return $this->activiteForm;
    }

    /**
     * @param ActiviteForm $activiteForm
     * @return ActiviteFormAwareTrait
     */
    public function setActiviteForm($activiteForm)
    {
        $this->activiteForm = $activiteForm;
        return $this;
    }
}