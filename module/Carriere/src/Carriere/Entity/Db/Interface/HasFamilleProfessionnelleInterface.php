<?php

namespace Carriere\Entity\Db\Interface;

use Carriere\Entity\Db\FamilleProfessionnelle;

interface HasFamilleProfessionnelleInterface
{
    public function getFamilleProfessionnelle(): ?FamilleProfessionnelle;

    public function setFamilleProfessionnelle(?FamilleProfessionnelle $famille): void;
}
