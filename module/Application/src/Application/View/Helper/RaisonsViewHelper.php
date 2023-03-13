<?php

namespace Application\View\Helper;

use Application\View\Renderer\PhpRenderer;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class RaisonsViewHelper extends AbstractHelper
{
    /**
     * @param array $raisons
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($raisons, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('raisons', ['raisons' => $raisons, 'options' => $options]);
    }
}