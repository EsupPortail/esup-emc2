<?php

namespace Application\Form\FicheMetierEtat;

trait FicheMetieEtatFormAwareTrait
{
    /** @var FicheMetierEtatForm */
    private $ficheMetierEtatForm;

    /**
     * @return FicheMetierEtatForm
     */
    public function getFicheMetierEtatForm()
    {
        return $this->ficheMetierEtatForm;
    }

    /**
     * @param FicheMetierEtatForm $ficheMetierEtatForm
     * @return FicheMetierEtatForm
     */
    public function setFicheMetierEtatForm($ficheMetierEtatForm)
    {
        $this->ficheMetierEtatForm = $ficheMetierEtatForm;
        return $this->ficheMetierEtatForm;
    }

}