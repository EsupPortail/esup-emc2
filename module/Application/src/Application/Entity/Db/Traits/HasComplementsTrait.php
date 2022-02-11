<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Complement;
use Doctrine\Common\Collections\ArrayCollection;

trait HasComplementsTrait {

    /** @var ArrayCollection (Complement) */
    private $complements;

    /**
     * @return Complement[]
     */
    public function getComplements(): array
    {
        return $this->complements->toArray();
    }

    public function addComplement(Complement $complement) : void
    {
        $this->complements->add($complement);
    }

    public function removeComplement(Complement $complement) : void
    {
        $this->complements->removeElement($complement);
    }

    public function getComplementsByType(string $type) : array
    {
        $complements = $this->getComplements();
        $complements = array_filter($complements, function(Complement $a) use ($type) { return $a->getType() === $type;});
        return $complements;
    }

}