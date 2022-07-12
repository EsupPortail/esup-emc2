<?php

namespace Application\Service;

use Laminas\View\Renderer\PhpRenderer;

trait RendererAwareTrait {

    /** @var PhpRenderer */
    private $renderer;

    /**
     * @return PhpRenderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param PhpRenderer $renderer
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }
}