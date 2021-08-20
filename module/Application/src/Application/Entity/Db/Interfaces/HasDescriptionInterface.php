<?php

namespace Application\Entity\Db\Interfaces;

interface HasDescriptionInterface {

    public function getDescription() : ?string;
    public function setDescription(?string $description) : HasDescriptionInterface;

}