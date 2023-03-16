<?php

namespace Metier\Form\Referentiel;

trait ReferentielFormAwareTrait {

    private ReferentielForm $referentielForm;

    public function getReferentielForm() : ReferentielForm
    {
        return $this->referentielForm;
    }

    public function setReferentielForm(ReferentielForm $referentielForm) : void
    {
        $this->referentielForm = $referentielForm;
    }

}