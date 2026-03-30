<?php

namespace Carriere\Entity\Db\Interface;

use Carriere\Entity\Db\Categorie;

interface HasCategorieInterface {

    public function getCategorie(): ?Categorie;
    public function setCategorie(?Categorie $categorie): void;
}
