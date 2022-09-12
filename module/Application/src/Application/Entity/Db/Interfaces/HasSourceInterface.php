<?php

namespace Application\Entity\Db\Interfaces;

use UnicaenDbImport\Entity\Db\Source;

interface HasSourceInterface {

    const SOURCE_EMC2 = 'EMC2';
    const SOURCE_OCTOPUS = 'OCTOPUS';
    const SOURCE_LAGAF = 'LAGAF';

    public function getSource() : ?Source;
    public function getIdSource() : ?string;
    public function setSource(?Source $source);
    public function setIdSource(?string $id);
}