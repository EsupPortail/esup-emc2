<?php

namespace Carriere\Service\Categorie;

trait CategorieServiceAwareTrait {

    private CategorieService $categorieService;

    public function getCategorieService() : CategorieService
    {
        return $this->categorieService;
    }

    public function setCategorieService(CategorieService $categorieService) : void
    {
        $this->categorieService = $categorieService;
    }

}