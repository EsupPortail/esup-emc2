<?php

namespace Formation\Form\DemandeExterne;

trait DemandeExterneFormAwareTrait {

    private DemandeExterneForm $demandeExterneForm;

    /**
     * @return DemandeExterneForm
     */
    public function getDemandeExterneForm(): DemandeExterneForm
    {
        return $this->demandeExterneForm;
    }

    /**
     * @param DemandeExterneForm $demandeExterneForm
     */
    public function setDemandeExterneForm(DemandeExterneForm $demandeExterneForm): void
    {
        $this->demandeExterneForm = $demandeExterneForm;
    }

}