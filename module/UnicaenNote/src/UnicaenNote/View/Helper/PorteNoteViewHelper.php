<?php

namespace UnicaenNote\View\Helper;

use Application\View\Renderer\PhpRenderer;
use UnicaenNote\Entity\Db\PorteNote;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class PorteNoteViewHelper extends AbstractHelper
{
    /**
     * @param PorteNote $portenote
     * @param string $mode
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(PorteNote $portenote, $mode = 'affichage', $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('portenote', ['portenote' => $portenote, 'mode' => $mode, 'options' => $options]);
    }
}