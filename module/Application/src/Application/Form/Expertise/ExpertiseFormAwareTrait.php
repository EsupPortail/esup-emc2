<?php

namespace Application\Form\Expertise;

trait ExpertiseFormAwareTrait {

    /** @var ExpertiseForm */
    private $expertiseForm;

    /**
     * @return ExpertiseForm
     */
    public function getExpertiseForm()
    {
        return $this->expertiseForm;
    }

    /**
     * @param ExpertiseForm $expertiseForm
     * @return ExpertiseForm
     */
    public function setExpertiseForm($expertiseForm)
    {
        $this->expertiseForm = $expertiseForm;
        return $this->expertiseForm;
    }
}
