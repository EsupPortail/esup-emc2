<?php

namespace Agent\View\Helper;

use Application\Entity\Db\Agent;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

/**
 * La raison derrière ce ViewHelper est de simplifier le plus possible l'écriture des actions qui présenteront ces
 * actions, et ainsi de pouvoir "oublier" le besoin de gérer les paramètres pour l'activation/désactivation des onglets.
 */
class AgentOngletViewHelper extends AbstractHelper
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


    public function __invoke(Agent $agent, string $current, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('agent-onglet-vh', ['agent' => $agent, 'current' => $current, 'parametres' => $this->getParametres(), 'options' => $options]);
    }
}
