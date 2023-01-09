<?php

namespace Indicateur\Form\Indicateur;

trait IndicateurFormAwareTrait {

    /** @var IndicateurForm */
    private $indicateurForm;

    /**
     * @return IndicateurForm
     */
    public function getIndicateurForm()
    {
        return $this->indicateurForm;
    }

    /**
     * @param IndicateurForm $indicateurForm
     * @return IndicateurForm
     */
    public function setIndicateurForm($indicateurForm)
    {
        $this->indicateurForm = $indicateurForm;
        return $this->indicateurForm;
    }


}