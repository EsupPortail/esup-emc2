<?php

namespace Formation\Form\Abonnement;

trait AbonnementFormAwareTrait {

    private AbonnementForm $abonnementForm;

    /**
     * @return AbonnementForm
     */
    public function getAbonnementForm(): AbonnementForm
    {
        return $this->abonnementForm;
    }

    /**
     * @param AbonnementForm $abonnementForm
     */
    public function setAbonnementForm(AbonnementForm $abonnementForm): void
    {
        $this->abonnementForm = $abonnementForm;
    }

}