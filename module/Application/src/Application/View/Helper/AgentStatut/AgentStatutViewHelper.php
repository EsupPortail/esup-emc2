<?php

namespace Application\View\Helper\AgentStatut;

use Application\Entity\Db\AgentStatut;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;


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
class AgentStatutViewHelper extends AbstractHelper
{
    /**
     * @param AgentStatut $statut
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($statut, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__."/partial"]]));

        return $view->partial('agent-status', ['statut' => $statut, 'options' => $options]);
    }
}