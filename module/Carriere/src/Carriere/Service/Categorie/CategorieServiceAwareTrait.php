<?php

namespace Carriere\Service\Categorie;

trait CategorieServiceAwareTrait {

    /** @var CategorieService */
    private $categorieService;

    /**
     * @return CategorieService
     */
    public function getCategorieService() : CategorieService
    {
        return $this->categorieService;
    }

    /**
     * @param CategorieService $categorieService
     * @return CategorieService
     */
    public function setCategorieService(CategorieService $categorieService) : CategorieService
    {
        $this->categorieService = $categorieService;
        return $this->categorieService;
    }


}