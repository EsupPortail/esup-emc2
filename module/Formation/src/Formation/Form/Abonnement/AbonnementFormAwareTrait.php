<?php

namespace Formation\Form\Abonnement;

trait AbonnementFormAwareTrait {

    private AbonnementForm $abonnementForm;

    public function getAbonnementForm(): AbonnementForm
    {
        return $this->abonnementForm;
    }

    public function setAbonnementForm(AbonnementForm $abonnementForm): void
    {
        $this->abonnementForm = $abonnementForm;
    }

}