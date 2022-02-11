<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Complement;

interface HasComplementsInterface {

    public function getComplements(): array;
    public function addComplement(Complement $complement) : void ;
    public function removeComplement(Complement $complement) : void ;
    public function getComplementsByType(string $type) : array ;
}