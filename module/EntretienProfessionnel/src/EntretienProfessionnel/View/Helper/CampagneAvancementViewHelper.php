<?php

namespace EntretienProfessionnel\View\Helper;

use Application\Entity\Db\Agent;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;


class CampagneAvancementViewHelper extends AbstractHelper
{
    /**
     * @param EntretienProfessionnel[] $entretiens
     * @param Agent[] $agents
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $entretiens, array $agents,array $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('campagne-avancement', ['entretiens' => $entretiens, 'agents' => $agents, 'options' => $options]);
    }
}