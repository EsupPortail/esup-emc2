<?php

namespace Formation\Service\FormationElement;

trait FormationElementServiceAwareTrait {

    private FormationElementService $formationElementService;

    public function getFormationElementService(): FormationElementService
    {
        return $this->formationElementService;
    }

    public function setFormationElementService(FormationElementService $formationElementService): void
    {
        $this->formationElementService = $formationElementService;
    }


}