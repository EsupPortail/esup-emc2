<?php

namespace Application\Form\MetierReferentiel;

trait MetierReferentielFormAwareTrait {

    /** @var MetierReferentielForm */
    private $metierReferentielForm;

    /**
     * @return MetierReferentielForm
     */
    public function getMetierReferentielForm()
    {
        return $this->metierReferentielForm;
    }

    /**
     * @param MetierReferentielForm $metierReferentielForm
     * @return MetierReferentielForm
     */
    public function setMetierReferentielForm(MetierReferentielForm $metierReferentielForm)
    {
        $this->metierReferentielForm = $metierReferentielForm;
        return $this->metierReferentielForm;
    }

}