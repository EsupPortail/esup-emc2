<?php

namespace UnicaenParametre\Service\Categorie;

trait CategorieServiceAwareTrait {

    /** @var CategorieService */
    private $parametreCategorieService;

    /**
     * @return CategorieService
     */
    public function getCategorieService(): CategorieService
    {
        return $this->parametreCategorieService;
    }

    /**
     * @param CategorieService $parametreCategorieService
     * @return CategorieService
     */
    public function setCategorieService(CategorieService $parametreCategorieService): CategorieService
    {
        $this->parametreCategorieService = $parametreCategorieService;
        return $this->parametreCategorieService;
    }

}