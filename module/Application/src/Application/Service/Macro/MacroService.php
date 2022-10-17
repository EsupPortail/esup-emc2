<?php

namespace Application\Service\Macro;

use DateTime;
use Laminas\View\Renderer\PhpRenderer;

class MacroService {

    /** @var PhpRenderer */
    protected $renderer;

    public function setRenderer($renderer) : void
    {
        $this->renderer = $renderer;
    }

    public function getDate() : string
    {
        $date = new DateTime();
        return $date->format('d/m/Y');
    }

    public function getDateTime() : string
    {
        $date = new DateTime();
        return $date->format('d/m/Y à H:i');
    }

    public function getAppName() : string
    {
        return 'EMC2';
    }

}