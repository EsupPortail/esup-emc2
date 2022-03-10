<?php

namespace Autoform\Service\Categorie;

trait CategorieServiceAwareTrait {

    /** @var CategorieService $categorieService */
    private $categorieService;

    /**
     * @return CategorieService
     */
    public function getCategorieService()
    {
        return $this->categorieService;
    }

    /**
     * @param CategorieService $categorieService
     * @return CategorieService
     */
    public function setCategorieService($categorieService)
    {
        $this->categorieService = $categorieService;
        return $this->categorieService;
    }


}