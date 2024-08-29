<?php

namespace Formation\Entity\Db\Traits;

use Doctrine\Common\Collections\Collection;
use Formation\Entity\Db\Inscription;

trait HasInscriptionsTrait
{
    private Collection $inscriptions;

    /** @return Inscription[] */
    public function getInscriptions(): array
    {
        return $this->inscriptions->toArray();
    }

    public function hasInscription(Inscription $inscription): bool
    {
        return $this->inscriptions->contains($inscription);
    }
    
    public function addInscription(Inscription $inscription): void
    {
        if (!$this->hasInscription($inscription)) $this->inscriptions->add($inscription);
    }

    public function removeInscription(Inscription $inscription): void
    {
        $this->inscriptions->removeElement($inscription);
    }
}