<?php

namespace UnicaenNote\View\Helper;

use Application\View\Renderer\PhpRenderer;
use UnicaenNote\Entity\Db\Note;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class NoteViewHelper extends AbstractHelper
{
    /**
     * @param Note $note
     * @param string $mode
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(Note $note, $mode = 'affichage', $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('note', ['note' => $note, 'mode' => $mode, 'options' => $options]);
    }
}