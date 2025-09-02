<?php

namespace Metier\Entity\Db\Trait;

use Doctrine\Common\Collections\Collection;
use Metier\Entity\Db\FamilleProfessionnelle;

trait HasFamillesProfessionnellesTrait
{

    private Collection $famillesProfessionnelles;

    /** @return FamilleProfessionnelle[] */
    public function getFamillesProfessionnelles(bool $withHisto = false): array
    {
        $familles =  $this->famillesProfessionnelles->toArray();
        if ($withHisto) $familles = array_filter($familles, function (FamilleProfessionnelle $famille) { return $famille->estNonHistorise();});
        usort($familles, function (FamilleProfessionnelle $a, FamilleProfessionnelle $b) { return $a->getLibelle() <=> $b->getLibelle();});
        return $familles;
    }

    public function hasFamilleProfessionnelle(FamilleProfessionnelle $famille, bool $withHisto = false) : bool
    {
        $familles =  $this->famillesProfessionnelles->toArray();
        if ($withHisto) $familles = array_filter($familles, function (FamilleProfessionnelle $famille) { return $famille->estNonHistorise();});
        return in_array($famille, $familles);
    }

    public function addFamilleProfessionnelle(FamilleProfessionnelle $famille): void
    {
        $this->famillesProfessionnelles->add($famille);
    }
    public function removeFamilleProfessionnelle(FamilleProfessionnelle $famille): void
    {
        $this->famillesProfessionnelles->removeElement($famille);
    }

    public function clearFamillesProfessionnelles(): void
    {
        $this->famillesProfessionnelles->clear();
    }
}