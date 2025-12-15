<?php

namespace Carriere\Entity\Db\Interface;

use Carriere\Entity\Db\Niveau;

interface HasNiveauCarriereInterface {

    public function getNiveauCarriere(): ?Niveau;
    public function setNiveauCarriere(?Niveau $niveauCarriere): void;
}
