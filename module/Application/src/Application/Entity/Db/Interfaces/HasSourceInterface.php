<?php

namespace Application\Entity\Db\Interfaces;

interface HasSourceInterface {

    public function getSource() : ?string;
    public function getIdSource() : ?string;
    public function setSource(?string $source);
    public function setIdSource(?string $id);
}