<?php

namespace Application\View\Helper;

use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class ActionIconViewHelper extends AbstractHelper
{
    /**
     * @param array $options
     *  with 'url', 'icone', 'isAllowed', 'titre', 'displayOff'
     *
     * @return string|Partial
     */
    public function __invoke($options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('action-icon', ['options' => $options]);
    }
}