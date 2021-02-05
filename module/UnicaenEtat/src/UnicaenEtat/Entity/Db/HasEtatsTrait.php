<?php

namespace UnicaenEtat\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Exception\RuntimeException;

trait HasEtatsTrait {

    /** @var ArrayCollection */
    private $etats;

    /**
     * @return ArrayCollection
     */
    public function getEtats() : ArrayCollection
    {
        return $this->etats;
    }

    /**
     * @return Etat|null
     */
    public function getEtatActif() : ?Etat
    {
        $etat = null;
        /** @var Etat $etat_ */
        foreach ($this->etats as $etat_) {
            if ($etat_->estNonHistorise()) {
                if ($etat !== null) throw new RuntimeException("Plusieurs Etats actifs de trouvés.");
                $etat = $etat_;
            }
        }
        return $etat;
    }

    /**
     * @param Etat $etat
     * @return self
     */
    public function addEtat(Etat $etat) : self
    {
        if (!$this->etats->contains($etat)) $this->etats->add($etat);
        return $this;
    }

    /**
     * @param Etat $etat
     * @return self
     */
    public function removeEtat(Etat $etat) : self
    {
        $this->etats->removeElement($etat);
        return $this;
    }

    /**
     * @return self
     */
    public function clearEtats() : self
    {
        $this->etats->clear();
        return $this;
    }
}