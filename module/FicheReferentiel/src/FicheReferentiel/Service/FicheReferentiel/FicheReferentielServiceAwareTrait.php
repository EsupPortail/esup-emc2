<?php

namespace FicheReferentiel\Service\FicheReferentiel;

trait FicheReferentielServiceAwareTrait {

    private FicheReferentielService $ficheReferentielService;

    public function getFicheReferentielService(): FicheReferentielService
    {
        return $this->ficheReferentielService;
    }

    public function setFicheReferentielService(FicheReferentielService $ficheReferentielService): void
    {
        $this->ficheReferentielService = $ficheReferentielService;
    }

}