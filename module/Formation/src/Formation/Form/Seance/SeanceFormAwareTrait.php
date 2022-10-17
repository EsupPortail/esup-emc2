<?php

namespace Formation\Form\Seance;

trait SeanceFormAwareTrait
{

    private SeanceForm $seanceFrom;

    public function getSeanceForm() : SeanceForm
    {
        return $this->seanceFrom;
    }

    public function setSeanceForm(SeanceForm $seanceFrom) : void
    {
        $this->seanceFrom = $seanceFrom;
    }


}