<?php

namespace Carriere\Entity\Db\Trait;

use Doctrine\Common\Collections\Collection;
use Carriere\Entity\Db\FamilleProfessionnelle;

trait HasFamilleProfessionnelleTrait
{
    private ?FamilleProfessionnelle $familleProfessionnelle = null;

    public function getFamilleProfessionnelle(): ?FamilleProfessionnelle
    {
        return $this->familleProfessionnelle;
    }

    public function setFamilleProfessionnelle(?FamilleProfessionnelle $familleProfessionnelle): void
    {
        $this->familleProfessionnelle = $familleProfessionnelle;
    }

}