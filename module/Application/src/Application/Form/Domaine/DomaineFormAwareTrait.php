<?php

namespace Application\Form\Domaine;

trait DomaineFormAwareTrait {

    /** @var DomaineForm $domaineForm */
    private $domaineForm;

    /**
     * @return DomaineForm
     */
    public function getDomaineForm()
    {
        return $this->domaineForm;
    }

    /**
     * @param DomaineForm $domaineForm
     * @return DomaineForm
     */
    public function setDomaineForm($domaineForm)
    {
        $this->domaineForm = $domaineForm;
        return $this->domaineForm;
    }


}