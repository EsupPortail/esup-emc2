<?php

namespace Application\View\Helper;

use Agent\Entity\Db\AgentGrade;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;


/**
 * Note : les clefs du tableau options sont les suivantes :
 * denomination     => le nom de l'agent impliqué (Billy Bob)
 * structure        => la structure impliquée (DSI)
 * grade            =>
 * corps            =>
 * bap              =>
 * periode          => la periode (01/01/2001 => 06/06/2006)
 * si non défini ou à vrai alors les données sont affichées
 */
class AgentGradeViewHelper extends AbstractHelper
{
    /**
     * @param AgentGrade $grade
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(AgentGrade $grade, array $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('agent-grade', ['grade' => $grade, 'options' => $options]);
    }
}