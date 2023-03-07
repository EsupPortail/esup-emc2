<?php

namespace Element\Form\SelectionCompetence;

trait SelectionCompetenceHydratorAwareTrait {

    private SelectionCompetenceHydrator $selectionCompetenceHydrator;

    /**
     * @return SelectionCompetenceHydrator
     */
    public function getSelectionCompetenceHydrator(): SelectionCompetenceHydrator
    {
        return $this->selectionCompetenceHydrator;
    }

    /**
     * @param SelectionCompetenceHydrator $selectionCompetenceHydrator
     */
    public function setSelectionCompetenceHydrator(SelectionCompetenceHydrator $selectionCompetenceHydrator): void
    {
        $this->selectionCompetenceHydrator = $selectionCompetenceHydrator;
    }

}