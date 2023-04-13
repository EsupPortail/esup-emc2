<?php

namespace Application\View\Helper;

use Application\Entity\Db\AgentSuperieur ;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;


class AgentSuperieurViewHelper extends AbstractHelper
{
    protected ?AgentSuperieur $superieur = null;
    protected array $options = [];

    public function __invoke(AgentSuperieur $superieur, array $options = []) : AgentSuperieurViewHelper
    {
        $this->superieur = $superieur;
        $this->options = $options;
        return $this;
    }

    public function __toString()
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('agent-superieur', ['superieur' => $this->superieur, 'options' => $this->options]);
    }
}