<?php

namespace Autoform\Form\Categorie;

trait CategorieFormAwareTrait {

    /** @var CategorieForm $categorieForm */
    private $categorieForm;

    /**
     * @return CategorieForm
     */
    public function getCategorieForm()
    {
        return $this->categorieForm;
    }

    /**
     * @param CategorieForm $categorieForm
     * @return CategorieForm
     */
    public function setCategorieForm($categorieForm)
    {
        $this->categorieForm = $categorieForm;
        return $this->categorieForm;
    }


}