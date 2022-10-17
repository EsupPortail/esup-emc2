<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agent;
use Application\View\Renderer\PhpRenderer;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;


class AgentViewHelper extends AbstractHelper
{
    /**
     * @param Agent $agent
     * @param string $mode
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($agent, $mode = 'affichage', $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('agent', ['agent' => $agent, 'mode' => $mode, 'options' => $options]);
    }
}