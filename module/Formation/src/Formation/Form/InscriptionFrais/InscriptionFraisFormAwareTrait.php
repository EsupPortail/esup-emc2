<?php

namespace Formation\Form\InscriptionFrais;

trait InscriptionFraisFormAwareTrait
{

    private InscriptionFraisForm $instanceFraisForm;

    public function getInscriptionFraisForm(): InscriptionFraisForm
    {
        return $this->instanceFraisForm;
    }

    public function setInscriptionFraisForm(InscriptionFraisForm $instanceFraisForm): void
    {
        $this->instanceFraisForm = $instanceFraisForm;
    }

}