<?php

namespace Observation\Entity\Trait;

use Doctrine\Common\Collections\Collection;
use Observation\Entity\Db\ObservationInstance;
use Observation\Entity\Db\ObservationType;

trait HasObservationsTrait
{
    private Collection $observations;

    public function getObservations(): Collection
    {
        return $this->observations;
    }

    public function hasObservation(ObservationInstance $observationInstance): bool
    {
        return $this->getObservations()->contains($observationInstance);
    }

    public function hasObservationWithType(ObservationType $observationType, bool $histo = false): bool
    {
        /** @var ObservationInstance $observation */
        foreach ($this->getObservations() as $observation) {
            if ($observation->getType() === $observationType AND (!$histo OR $observation->estNonHistorise())) return true;
        }
        return false;
    }

    public function getObservationWithTypeCode(string $code, bool $histo = false): ?ObservationInstance
    {
        /** @var ObservationInstance $observation */
        foreach ($this->getObservations() as $observation) {
            if ($observation->getType()->getCode() === $code AND (!$histo OR $observation->estNonHistorise())) return $observation;
        }
        return null;
    }

    public function addObservation(ObservationInstance $observationInstance): void
    {
        $this->observations->add($observationInstance);
    }

    public function removeObservation(ObservationInstance $observationInstance): void
    {
        $this->observations->removeElement($observationInstance);
    }

}