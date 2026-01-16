<?php

namespace Carriere\Entity\Db\Interface;

use Carriere\Entity\Db\FamilleProfessionnelle;

interface HasFamillesProfessionnellesInterface
{
    public function getFamillesProfessionnelles(bool $withHisto = false): array;
    public function hasFamilleProfessionnelle(FamilleProfessionnelle $famille, bool $withHisto = false) : bool;
    public function addFamilleProfessionnelle(FamilleProfessionnelle $famille): void;
    public function removeFamilleProfessionnelle(FamilleProfessionnelle $famille): void;
    public function clearFamillesProfessionnelles(): void;
}
