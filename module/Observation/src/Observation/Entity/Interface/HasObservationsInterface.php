<?php

namespace Observation\Entity\Interface;

use Doctrine\Common\Collections\Collection;
use Observation\Entity\Db\ObservationInstance;
use Observation\Entity\Db\ObservationType;

interface HasObservationsInterface
{
    public function getObservations(): Collection;
    public function hasObservation(ObservationInstance $observationInstance): bool;
    public function hasObservationWithType(ObservationType $observationType, bool $histo = false): bool;

    public function getObservationWithTypeCode(string $code, bool $histo = false): ?ObservationInstance;
    public function addObservation(ObservationInstance $observationInstance): void;
    public function removeObservation(ObservationInstance $observationInstance): void;

}