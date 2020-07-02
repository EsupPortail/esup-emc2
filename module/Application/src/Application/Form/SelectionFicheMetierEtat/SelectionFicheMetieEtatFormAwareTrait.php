<?php

namespace Application\Form\SelectionFicheMetierEtat;

trait SelectionFicheMetieEtatFormAwareTrait
{
    /** @var SelectionFicheMetierEtatForm */
    private $selectionFicheMetierEtatForm;

    /**
     * @return SelectionFicheMetierEtatForm
     */
    public function getSelectionFicheMetierEtatForm()
    {
        return $this->selectionFicheMetierEtatForm;
    }

    /**
     * @param SelectionFicheMetierEtatForm $selectionFicheMetierEtatForm
     * @return SelectionFicheMetierEtatForm
     */
    public function setSelectionFicheMetierEtatForm($selectionFicheMetierEtatForm)
    {
        $this->selectionFicheMetierEtatForm = $selectionFicheMetierEtatForm;
        return $this->selectionFicheMetierEtatForm;
    }

}