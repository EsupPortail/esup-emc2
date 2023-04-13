<?php

namespace Application\View\Helper;

use Application\Entity\Db\AgentAutorite ;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;


class AgentAutoriteViewHelper extends AbstractHelper
{
    protected ?AgentAutorite $autorite = null;
    protected array $options = [];

    public function __invoke(AgentAutorite $autorite, array $options = []) : AgentAutoriteViewHelper
    {
        $this->autorite = $autorite;
        $this->options = $options;
        return $this;
    }

    public function __toString()
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('agent-autorite', ['autorite' => $this->autorite, 'options' => $this->options]);
    }
}