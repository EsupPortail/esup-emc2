<?php

namespace UnicaenEtat\View\Helper;

use Application\View\Renderer\PhpRenderer;
use UnicaenEtat\Entity\Db\Action;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class ActionBadgeViewHelper extends AbstractHelper
{
    /**
     * @param Action $action
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($action, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('actionbadge', ['action' => $action, 'options' => $options]);
    }
}