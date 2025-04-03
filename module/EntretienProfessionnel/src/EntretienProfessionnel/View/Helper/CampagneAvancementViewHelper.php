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
    public ?PhpRenderer $renderer = null;
    public ?array $entretiens = [];
    public ?array $agents = [];
    public ?array $options = [];

    /**
     * @param EntretienProfessionnel[] $entretiens
     * @param Agent[]|null $agents
     * @param int|null $nbAgents
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $entretiens, ?array $agents = null, ?int $nbAgents = null, array $options = []): string|Partial
    {
        $this->entretiens = $entretiens;
        $this->agents = $agents;
        $this->options = $options;

        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('campagne-avancement', ['entretiens' => $entretiens, 'agents' => $agents, 'nbAgents' => $nbAgents, 'options' => $options]);
    }

    public function __toString() {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('campagne-avancement', ['entretiens' => $this->entretiens, 'agents' => $this->agents, 'options' => $this->options]);
    }

    public function render(string $mode = 'div') {
        $view = $this->renderer;
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('campagne-avancement', ['entretiens' => $this->entretiens, 'agents' => $this->agents, 'options' => ['mode' => $mode]]);
    }
}