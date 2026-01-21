<?php

namespace FicheMetier\Entity\Db\Interface;

use Doctrine\ORM\QueryBuilder;
use FicheMetier\Entity\Db\MissionElement;
use FicheMetier\Entity\Db\Mission;

interface HasMissionsPrincipalesInterface
{
    /** @return MissionElement[] */
    public function getMissions(bool $withHisto = false): array;
    public function addMission(MissionElement $missionElement): void;
    public function removeMission(MissionElement $missionElement): void;
    public function clearMissions(): void;
    public function hasMission(Mission $mission): bool;

    public function getMissionsAsList(bool $withHisto = false): string;
    static public function decorateWithMissionPrincipale(QueryBuilder $qb, string $entityName,  ?Mission $mission = null, bool $withHisto = false) : QueryBuilder;

}