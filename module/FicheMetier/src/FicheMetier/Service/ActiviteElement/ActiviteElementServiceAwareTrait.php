<?php

namespace FicheMetier\Service\ActiviteElement;

trait ActiviteElementServiceAwareTrait {

    private ActiviteElementService $activiteElementService;

    public function getActiviteElementService(): ActiviteElementService
    {
        return $this->activiteElementService;
    }

    public function setActiviteElementService(ActiviteElementService $activiteElementService): void
    {
        $this->activiteElementService = $activiteElementService;
    }

}
