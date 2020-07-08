<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agent;
use Application\Entity\Db\ParcoursDeFormation;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class ParcoursDeFormationViewHelper extends AbstractHelper
{
    /**
     * @param ParcoursDeFormation $parcours
     * @param Agent $agent
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($parcours, $agent = null, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('parcours-de-formation', ['parcours' => $parcours, 'agent' => $agent, 'options' => $options]);
    }
}