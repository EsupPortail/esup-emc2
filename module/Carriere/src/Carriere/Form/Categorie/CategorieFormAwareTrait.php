<?php

namespace Carriere\Form\Categorie;

trait CategorieFormAwareTrait {

    private CategorieForm $categorieForm;

    public function getCategorieForm() : CategorieForm
    {
        return $this->categorieForm;
    }

    public function setCategorieForm(CategorieForm $categorieForm) : void
    {
        $this->categorieForm = $categorieForm;
    }
}