<?php

namespace EmploiRepere\Form\EmploiRepereCodeFonctionFicheMetier;

trait EmploiRepereCodeFonctionFicheMetierFormAwareTrait {

    private EmploiRepereCodeFonctionFicheMetierForm $emploiRepereCodeFonctionFicheMetierForm;

    public function getEmploiRepereCodeFonctionFicheMetierForm(): EmploiRepereCodeFonctionFicheMetierForm
    {
        return $this->emploiRepereCodeFonctionFicheMetierForm;
    }

    public function setEmploiRepereCodeFonctionFicheMetierForm(EmploiRepereCodeFonctionFicheMetierForm $emploiRepereCodeFonctionFicheMetierForm): void
    {
        $this->emploiRepereCodeFonctionFicheMetierForm = $emploiRepereCodeFonctionFicheMetierForm;
    }

}
