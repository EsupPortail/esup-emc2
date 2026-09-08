<?php

namespace FicheMetier\Service\FicheMetierCategorie;

trait FicheMetierCategorieServiceAwareTrait
{

    private FicheMetierCategorieService $ficheMetierCategorieService;

    public function getFicheMetierCategorieService(): FicheMetierCategorieService
    {
        return $this->ficheMetierCategorieService;
    }

    public function setFicheMetierCategorieService(FicheMetierCategorieService $ficheMetierCategorieService): void
    {
        $this->ficheMetierCategorieService = $ficheMetierCategorieService;
    }

}
