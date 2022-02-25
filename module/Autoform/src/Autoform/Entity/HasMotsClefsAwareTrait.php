<?php

namespace Autoform\Entity;

trait HasMotsClefsAwareTrait {

    /** @var string */
    private $motsClefs;

    /**
     * @return string|null
     */
    public function getMotsClefs(): ?string
    {
        return $this->motsClefs;
    }

    public function hasMotsClefs(array $mots) : bool
    {
        $motsClefs = explode(';', $this->getMotsClefs());
        foreach ($mots as $mot) {
            if (array_search($mot, $motsClefs) === false) return false;
        }
        return true;
    }



}