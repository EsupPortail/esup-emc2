<?php

namespace Metier\Form\Referentiel;

trait ReferentielFormAwareTrait {

    /** @var ReferentielForm */
    private $referentielForm;

    /**
     * @return ReferentielForm
     */
    public function getReferentielForm() : ReferentielForm
    {
        return $this->referentielForm;
    }

    /**
     * @param ReferentielForm $referentielForm
     * @return ReferentielForm
     */
    public function setReferentielForm(ReferentielForm $referentielForm) : ReferentielForm
    {
        $this->referentielForm = $referentielForm;
        return $this->referentielForm;
    }

}