<?php

namespace FicheMetier\Service\Activite;

trait ActiviteServiceAwareTrait
{
    private ActiviteService $activiteService;

    public function getActiviteService(): ActiviteService
    {
        return $this->activiteService;
    }

    public function setActiviteService(ActiviteService $activiteService): void
    {
        $this->activiteService = $activiteService;
    }

}
