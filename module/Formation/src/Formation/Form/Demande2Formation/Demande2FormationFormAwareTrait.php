<?php

namespace Formation\Form\Demande2Formation;

trait Demande2FormationFormAwareTrait {

    private Demande2FormationForm $demande2formationForm;

    public function getDemande2formationForm(): Demande2FormationForm
    {
        return $this->demande2formationForm;
    }

    public function setDemande2formationForm(Demande2FormationForm $demande2formationForm): void
    {
        $this->demande2formationForm = $demande2formationForm;
    }

}