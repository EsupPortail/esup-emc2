<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agent;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;


class AgentViewHelper extends AbstractHelper
{
    public function __invoke(Agent $agent, string $mode = 'affichage', array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('agent', ['agent' => $agent, 'mode' => $mode, 'options' => $options]);
    }
}