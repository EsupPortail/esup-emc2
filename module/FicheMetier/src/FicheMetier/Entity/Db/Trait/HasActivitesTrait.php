<?php

namespace FicheMetier\Entity\Db\Trait;

use Doctrine\Common\Collections\Collection;
use FicheMetier\Entity\Db\Activite;
use FicheMetier\Entity\Db\ActiviteElement;
use FicheMetier\Entity\Db\FicheMetierMission;

trait HasActivitesTrait
{
    private Collection $activites;

    /** @return ActiviteElement[] */
    public function getActivites(bool $withHisto = false): array
    {
        $activites = $this->activites->toArray();
        if (!$withHisto) {
            $missions = array_filter($activites, function (ActiviteElement $element) {
                return $element->estNonHistorise() AND $element->getActivite()->estNonHistorise();
            });
        }

        usort($activites, function (ActiviteElement $a, ActiviteElement $b) {
           if ($a->getPosition() !== $b->getPosition()) { return $a->getPosition() <=> $b->getPosition(); }
           return $a->getActivite()->getLibelle() <=> $b->getActivite()->getLibelle();
        });
        return $activites;
    }

    public function addActivite(ActiviteElement $element): void
    {
        $this->activites->add($element);
    }

    public function removeActivite(ActiviteElement $element): void
    {
        $this->activites->removeElement($element);
    }

    public function clearActivites(): void
    {
        $this->activites->clear();
    }

    public function hasActivite(Activite $activite): bool
    {
        /** @var ActiviteElement[] $activites */
        $activites = $this->activites->toArray();
        foreach ($activites as $activiteElement) {
            if ($activiteElement->getActivite() === $activite) return true;
        }
        return false;
    }
}