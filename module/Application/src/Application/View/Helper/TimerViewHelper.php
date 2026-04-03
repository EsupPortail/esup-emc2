<?php

namespace Application\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

class TimerViewHelper extends AbstractHelper
{
    /**
     * @param array $timing (string:etape, float:temps)
     * @param bool $debug
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $timing, bool $debug = true, array $options = []):  string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('timer', ['timing' => $timing, 'debug' => $debug, 'options' => $options]);
    }
}
