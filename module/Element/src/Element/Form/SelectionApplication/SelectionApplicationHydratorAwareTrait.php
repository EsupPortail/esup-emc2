<?php

namespace Element\Form\SelectionApplication;

trait SelectionApplicationHydratorAwareTrait {

    private SelectionApplicationHydrator $selectionApplicationHydrator;

    public function getSelectionApplicationHydrator(): SelectionApplicationHydrator
    {
        return $this->selectionApplicationHydrator;
    }

    public function setSelectionApplicationHydrator(SelectionApplicationHydrator $selectionApplicationHydrator): void
    {
        $this->selectionApplicationHydrator = $selectionApplicationHydrator;
    }

}