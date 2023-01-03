<?php

namespace Application\Entity\Db\Traits;

trait HasDescriptionTrait {

    private ?string $description = null;

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description) : void
    {
        $this->description = $description;
    }
}