<?php

namespace FicheMetier\Service\TendanceType;

trait TendanceTypeServiceAwareTrait
{
    private TendanceTypeService $tendanceTypeService;

    public function getTendanceTypeService(): TendanceTypeService
    {
        return $this->tendanceTypeService;
    }

    public function setTendanceTypeService(TendanceTypeService $tendanceTypeService): void
    {
        $this->tendanceTypeService = $tendanceTypeService;
    }

}