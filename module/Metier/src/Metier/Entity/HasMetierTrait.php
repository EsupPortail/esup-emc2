<?php

namespace Metier\Entity;

use Metier\Entity\Db\Metier;

trait HasMetierTrait
{
    private ?Metier $metier = null;

    public function getMetier() : ?Metier
    {
        return $this->metier;
    }

    public function setMetier(?Metier $metier) : void
    {
        $this->metier = $metier;
    }
}