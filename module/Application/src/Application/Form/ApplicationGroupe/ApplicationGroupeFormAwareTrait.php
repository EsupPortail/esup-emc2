<?php

namespace Application\Form\ApplicationGroupe;

trait ApplicationGroupeFormAwareTrait {

    /** @var ApplicationGroupeForm */
    private $applicationGroupeForm;

    /**
     * @return ApplicationGroupeForm
     */
    public function getApplicationGroupeForm()
    {
        return $this->applicationGroupeForm;
    }

    /**
     * @param ApplicationGroupeForm $applicationGroupeForm
     * @return ApplicationGroupeForm
     */
    public function setApplicationGroupeForm($applicationGroupeForm)
    {
        $this->applicationGroupeForm = $applicationGroupeForm;
        return $this->applicationGroupeForm;
    }


}