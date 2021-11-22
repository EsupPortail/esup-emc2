<?php

namespace Application\Entity\Db\Interfaces;

interface HasSourceInterface {

    const SOURCE_EMC2 = 'EMC2';
    const SOURCE_LAGAF = 'LAGAF';

    public function getSource() : ?string;
    public function getIdSource() : ?string;
    public function setSource(?string $source);
    public function setIdSource(?string $id);
}