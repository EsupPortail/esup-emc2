<?php

namespace Application\Entity\Db\Interfaces;

interface HasSourceInterface {

    public function getSource() : ?string;
    public function getIdSource() : ?int;
    public function setSource(?string $source);
    public function setIdSource(?int $id);
}