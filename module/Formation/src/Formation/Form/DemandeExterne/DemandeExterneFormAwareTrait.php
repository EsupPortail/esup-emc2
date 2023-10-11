<?php

namespace Formation\Form\DemandeExterne;

trait DemandeExterneFormAwareTrait {

    private DemandeExterneForm $demandeExterneForm;

    public function getDemandeExterneForm(): DemandeExterneForm
    {
        return $this->demandeExterneForm;
    }

    public function setDemandeExterneForm(DemandeExterneForm $demandeExterneForm): void
    {
        $this->demandeExterneForm = $demandeExterneForm;
    }

}