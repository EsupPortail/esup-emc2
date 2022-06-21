<?php

namespace Application\Service\RgpdRenderer;

use Application\Event\RgpdRenderer\RgpdRendererEvenement;

trait RgpdRendererEvenementAwareTrait {

    /** @var RgpdRendererEvenement */
    private $rgpdRendererEvenement;

    /**
     * @return RgpdRendererEvenement
     */
    public function getRgpdRendererEvenement(): RgpdRendererEvenement
    {
        return $this->rgpdRendererEvenement;
    }

    /**
     * @param RgpdRendererEvenement $rgpdRendererEvenement
     */
    public function setRgpdRendererEvenement(RgpdRendererEvenement $rgpdRendererEvenement): void
    {
        $this->rgpdRendererEvenement = $rgpdRendererEvenement;
    }
}