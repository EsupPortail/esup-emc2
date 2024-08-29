<?php

namespace Application\Service\Macro;

use DateTime;
use Laminas\View\Renderer\PhpRenderer;

class MacroService {

    /** @var PhpRenderer */
    protected $renderer;
    protected $vars = [];

    public function setRenderer($renderer) : void
    {
        $this->renderer = $renderer;
    }

    public function setVars(array $vars) : void
    {
        $this->vars = $vars;
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

    /** @noinspection PhpUnused [Macro: EMC2#AfficherTexte] */
    public function toStringTexte(): string
    {
        if (!isset($this->vars['texte'])) return "";
        else return $this->vars['texte'];
    }
}