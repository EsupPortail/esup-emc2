<?php

namespace Application\View\Helper;

use Agent\Entity\Db\AgentAffectation;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

/**
 * Note : les clefs du tableau options sont les suivantes :
 * id               => l'identifiant du status (OSE-2017-17566-14)
 * denomination     => le nom de l'agent impliqué (Billy Bob)
 * structure        => la structure impliquée (DSI)
 * periode          => la periode (01/01/2001 => 06/06/2006)
 * statut           => la liste des statuts
 *
 * si non défini ou à vrai alors les données sont affichées
 */
class AgentAffectationViewHelper extends AbstractHelper
{
    /**
     * @param AgentAffectation $affectation
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(AgentAffectation $affectation, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('agent-affectation', ['affectation' => $affectation, 'options' => $options]);
    }
}