<?php

namespace Element\Form\CompetenceSynonyme;

trait CompetenceSynonymeFormAwareTrait
{
    private CompetenceSynonymeForm $competenceSynonymeForm;

    public function getCompetenceSynonymeForm(): CompetenceSynonymeForm
    {
        return $this->competenceSynonymeForm;
    }

    public function setCompetenceSynonymeForm(CompetenceSynonymeForm $competenceSynonymeForm): void
    {
        $this->competenceSynonymeForm = $competenceSynonymeForm;
    }

}
