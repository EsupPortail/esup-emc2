<?php

namespace EmploiRepere\Service\EmploiRepereCodeFonctionFicheMetier;

trait EmploiRepereCodeFonctionFicheMetierServiceAwareTrait {

    private EmploiRepereCodeFonctionFicheMetierService $emploiRepereCodeFonctionFicheMetierService;

    public function getEmploiRepereCodeFonctionFicheMetierService(): EmploiRepereCodeFonctionFicheMetierService
    {
        return $this->emploiRepereCodeFonctionFicheMetierService;
    }

    public function setEmploiRepereCodeFonctionFicheMetierService(EmploiRepereCodeFonctionFicheMetierService $emploiRepereCodeFonctionFicheMetierService): void
    {
        $this->emploiRepereCodeFonctionFicheMetierService = $emploiRepereCodeFonctionFicheMetierService;
    }

}
