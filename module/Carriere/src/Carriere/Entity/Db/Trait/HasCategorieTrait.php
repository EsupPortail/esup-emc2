<?php

namespace Carriere\Entity\Db\Trait;

use Carriere\Entity\Db\Categorie;

trait HasCategorieTrait
{
    private ?Categorie $categorie = null;

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): void
    {
        $this->categorie = $categorie;
    }
}
