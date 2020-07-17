<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agent;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;

class AgentCompetenceViewHelper extends AbstractHelper
{
    /**
     * @param Agent $agent
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($agent, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('agent-competence', ['agent' => $agent, 'options' => $options]);
    }
}
