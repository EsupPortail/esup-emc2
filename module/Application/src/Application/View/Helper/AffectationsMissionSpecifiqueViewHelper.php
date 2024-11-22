<?php

namespace Application\View\Helper;

use Application\Entity\Db\AgentMissionSpecifique;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

class AffectationsMissionSpecifiqueViewHelper extends AbstractHelper
{
    /**
     * @param AgentMissionSpecifique[] $affectations
     * @param array $options
     * @return string|Partial
     *
     * OPTION
     * retour => l'url de retour post action (default: null))
     * 'display-agent' => affiche la colonne agent (default: true)
     */
    public function __invoke(array $affectations, array $options = []): Partial|string
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('affectations-mission-specifique', ['affectations' => $affectations, 'options' => $options]);
    }
}