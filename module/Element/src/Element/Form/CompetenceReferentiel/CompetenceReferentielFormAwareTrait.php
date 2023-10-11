<?php

namespace Element\Form\CompetenceReferentiel;

trait CompetenceReferentielFormAwareTrait {

    private CompetenceReferentielForm  $competenceReferentielForm;

    public function getCompetenceReferentielForm() : CompetenceReferentielForm
    {
        return $this->competenceReferentielForm;
    }

    public function setCompetenceReferentielForm(CompetenceReferentielForm $competenceReferentielForm) : void
    {
        $this->competenceReferentielForm = $competenceReferentielForm;
    }

}