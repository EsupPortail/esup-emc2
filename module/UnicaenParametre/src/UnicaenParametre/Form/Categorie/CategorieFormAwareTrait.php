<?php

namespace UnicaenParametre\Form\Categorie;

trait CategorieFormAwareTrait {

    /** @var CategorieForm */
    private $categorieForm;

    /**
     * @return CategorieForm
     */
    public function getCategorieForm(): CategorieForm
    {
        return $this->categorieForm;
    }

    /**
     * @param CategorieForm $categorieForm
     * @return CategorieForm
     */
    public function setCategorieForm(CategorieForm $categorieForm): CategorieForm
    {
        $this->categorieForm = $categorieForm;
        return $this->categorieForm;
    }
}