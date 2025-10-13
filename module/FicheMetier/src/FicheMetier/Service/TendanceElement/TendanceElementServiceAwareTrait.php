<?php

namespace FicheMetier\Service\TendanceElement;

trait TendanceElementServiceAwareTrait
{
    private TendanceElementService $tendanceElementService;

    public function getTendanceElementService(): TendanceElementService
    {
        return $this->tendanceElementService;
    }

    public function setTendanceElementService(TendanceElementService $tendanceElementService): void
    {
        $this->tendanceElementService = $tendanceElementService;
    }

}