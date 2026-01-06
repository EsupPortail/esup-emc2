<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agent;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;


class AgentViewHelper extends AbstractHelper
{
    private array $parametres = [];

    public function getParametres(): array
    {
        return $this->parametres;
    }

    public function setParametres(array $parametres): void
    {
        $this->parametres = $parametres;
    }


    public function __invoke(Agent $agent, string $mode = 'affichage', array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        $options['parametres'] = $this->parametres;

        return $view->partial('agent', ['agent' => $agent, 'mode' => $mode, 'options' => $options]);
    }
}