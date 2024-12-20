<?php

namespace FichePoste\Form\Expertise;

trait ExpertiseFormAwareTrait {

    private ExpertiseForm $expertiseForm;

    public function getExpertiseForm(): ExpertiseForm
    {
        return $this->expertiseForm;
    }

    public function setExpertiseForm(ExpertiseForm $expertiseForm): void
    {
        $this->expertiseForm = $expertiseForm;
    }
}
