<?php

namespace Application\Form\MetierReference;

trait MetierReferenceFormAwareTrait {

    /** @var MetierReferenceForm */
    private $metierReferenceForm;

    /**
     * @return MetierReferenceForm
     */
    public function getMetierReferenceForm()
    {
        return $this->metierReferenceForm;
    }

    /**
     * @param MetierReferenceForm $metierReferenceForm
     * @return MetierReferenceForm
     */
    public function setMetierReferenceForm(MetierReferenceForm $metierReferenceForm)
    {
        $this->metierReferenceForm = $metierReferenceForm;
        return $this->metierReferenceForm;
    }
}