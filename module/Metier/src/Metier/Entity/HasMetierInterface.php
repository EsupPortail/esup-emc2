<?php

namespace Metier\Entity;

use Metier\Entity\Db\Metier;

interface HasMetierInterface
{
    public function getMetier() : ?Metier;
    public function setMetier(?Metier $metier) : void;
}