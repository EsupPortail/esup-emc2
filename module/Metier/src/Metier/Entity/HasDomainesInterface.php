<?php

namespace Metier\Entity;

use Metier\Entity\Db\Domaine;

interface HasDomainesInterface
{
    public function getDomaines() : array;
    public function hasDomaine(Domaine $domaine) : bool;
    public function addDomaine(Domaine $domaine) : void;
    public function removeDomaine(Domaine $domaine) : void;
}