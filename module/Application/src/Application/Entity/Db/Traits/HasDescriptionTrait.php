<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;

trait HasDescriptionTrait {

    /** @var string */
    private $description;

    /**
     * @return string|null
     */
    public function getDescription() : ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return HasDescriptionInterface
     */
    public function setDescription(?string $description) : HasDescriptionInterface
    {
        $this->description = $description;
        return $this;
    }
}