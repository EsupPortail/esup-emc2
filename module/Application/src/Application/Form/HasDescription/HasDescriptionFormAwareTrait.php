<?php

namespace Application\Form\HasDescription;

trait HasDescriptionFormAwareTrait {

    /** @var HasDescriptionForm */
    private $hasDescriptionForm;

    /**
     * @return HasDescriptionForm
     */
    public function getHasDescriptionForm(): HasDescriptionForm
    {
        return $this->hasDescriptionForm;
    }

    /**
     * @param HasDescriptionForm $hasDescriptionForm
     * @return HasDescriptionForm
     */
    public function setHasDescriptionForm(HasDescriptionForm $hasDescriptionForm): HasDescriptionForm
    {
        $this->hasDescriptionForm = $hasDescriptionForm;
        return $this->hasDescriptionForm;
    }

}