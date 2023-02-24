<?php

namespace Metier\Entity;

use Doctrine\Common\Collections\Collection;
use Metier\Entity\Db\Domaine;

trait HasDomainesTrait
{
    private Collection $domaines;

    /** @return Domaine[] */
    public function getDomaines() : array
    {
        return $this->domaines->toArray();
    }

    public function hasDomaine(Domaine $domaine) : bool
    {
        return $this->domaines->contains($domaine);
    }

    public function addDomaine(Domaine $domaine) : void
    {
        if (! $this->hasDomaine($domaine)) $this->domaines->add($domaine);
    }
    public function removeDomaine(Domaine $domaine) : void
    {
        $this->domaines->removeElement($domaine);
    }
}