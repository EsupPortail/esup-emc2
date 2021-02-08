<?php

namespace Metier\Form\Domaine;

trait DomaineFormAwareTrait {

    /** @var DomaineForm $domaineForm */
    private $domaineForm;

    /**
     * @return DomaineForm
     */
    public function getDomaineForm() : DomaineForm
    {
        return $this->domaineForm;
    }

    /**
     * @param DomaineForm $domaineForm
     * @return DomaineForm
     */
    public function setDomaineForm(DomaineForm $domaineForm) : DomaineForm
    {
        $this->domaineForm = $domaineForm;
        return $this->domaineForm;
    }


}