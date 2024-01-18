<?php

namespace Observation\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;
use Observation\Entity\Db\ObservationInstance;

class ObservationInstanceViewHelper extends AbstractHelper
{
    public function __invoke(ObservationInstance $observation, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('observation-instance', ['observation' => $observation, 'options' => $options]);
    }
}