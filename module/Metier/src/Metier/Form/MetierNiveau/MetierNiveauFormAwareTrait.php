<?php

namespace Metier\Form\MetierNiveau;

trait MetierNiveauFormAwareTrait {

    /** @var MetierNiveauForm */
    private $metierNiveauForm;

    /**
     * @return MetierNiveauForm
     */
    public function getMetierNiveauForm(): MetierNiveauForm
    {
        return $this->metierNiveauForm;
    }

    /**
     * @param MetierNiveauForm $metierNiveauForm
     * @return MetierNiveauForm
     */
    public function setMetierNiveauForm(MetierNiveauForm $metierNiveauForm): MetierNiveauForm
    {
        $this->metierNiveauForm = $metierNiveauForm;
        return $this->metierNiveauForm;
    }

}