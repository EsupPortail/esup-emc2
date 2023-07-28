<?php

namespace Formation\View\Helper;

use Formation\Entity\Db\FormationInstance;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;


class FormationInstanceInformationsViewHelper extends AbstractHelper
{
    /**
     * @param FormationInstance $instance
     * @param string $mode
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(FormationInstance $instance, string $mode = 'liste', array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('formation-instance-informations', ['instance' => $instance, 'mode' => $mode, 'options' => $options]);
    }
}