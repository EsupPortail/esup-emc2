<?php

namespace Carriere\Service\NiveauFonction;

trait NiveauFonctionServiceAwareTrait
{
    private NiveauFonctionService $niveauFonctionService;

    public function getNiveauFonctionService(): NiveauFonctionService
    {
        return $this->niveauFonctionService;
    }

    public function setNiveauFonctionService(NiveauFonctionService $niveauFonctionService): void
    {
        $this->niveauFonctionService = $niveauFonctionService;
    }

}
