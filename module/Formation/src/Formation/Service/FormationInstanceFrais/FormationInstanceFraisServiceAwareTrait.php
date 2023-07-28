<?php

namespace Formation\Service\FormationInstanceFrais;

trait FormationInstanceFraisServiceAwareTrait
{

    private FormationInstanceFraisService $formationInstanceFraisService;

    public function getFormationInstanceFraisService(): FormationInstanceFraisService
    {
        return $this->formationInstanceFraisService;
    }

    public function setFormationInstanceFraisService(FormationInstanceFraisService $formationInstanceFraisService): void
    {
        $this->formationInstanceFraisService = $formationInstanceFraisService;
    }


}