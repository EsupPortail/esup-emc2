<?php

namespace Formation\Service\FormationElement;

trait FormationElementServiceAwareTrait {

    /** @var FormationElementService */
    private $formationElementService;

    /**
     * @return FormationElementService
     */
    public function getFormationElementService(): FormationElementService
    {
        return $this->formationElementService;
    }

    /**
     * @param FormationElementService $formationElementService
     * @return FormationElementService
     */
    public function setFormationElementService(FormationElementService $formationElementService): FormationElementService
    {
        $this->formationElementService = $formationElementService;
        return $this->formationElementService;
    }


}