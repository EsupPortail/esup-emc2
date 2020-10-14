<?php

namespace UnicaenEtat\View\Helper;

use Application\View\Renderer\PhpRenderer;
use UnicaenEtat\Entity\Db\ActionType;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class ActionTypeViewHelper extends AbstractHelper
{
    /**
     * @param ActionType $actionType
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($actionType, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('actiontype', ['actionType' => $actionType, 'options' => $options]);
    }
}