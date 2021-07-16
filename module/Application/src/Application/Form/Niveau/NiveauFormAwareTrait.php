<?php

namespace Application\Form\Niveau;

trait NiveauFormAwareTrait {

    /** @var NiveauForm */
    private $niveauForm;

    /**
     * @return NiveauForm
     */
    public function getNiveauForm(): NiveauForm
    {
        return $this->niveauForm;
    }

    /**
     * @param NiveauForm $niveauForm
     * @return NiveauForm
     */
    public function setNiveauForm(NiveauForm $niveauForm): NiveauForm
    {
        $this->niveauForm = $niveauForm;
        return $this->niveauForm;
    }

}