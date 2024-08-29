<?php

namespace Formation\Entity\Db\Interfaces;

use Formation\Entity\Db\Inscription;

interface HasInscriptionsInterfaces
{
    public function getInscriptions(): array;
    public function hasInscription(Inscription $inscription): bool;
    public function addInscription(Inscription $inscription): void;
    public function removeInscription(Inscription $inscription): void;
}